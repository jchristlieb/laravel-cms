<?php

namespace Bambamboole\LaravelCms\Backend\Form;

use Bambamboole\LaravelCms\Backend\Form\Fields\Field;
use Bambamboole\LaravelCms\Core\Models\BaseModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator as ValidatorFactory;
use Illuminate\Validation\Validator;

abstract class Form implements \JsonSerializable
{
    protected array $data;

    protected BaseModel $resource;

    protected bool $isCreateForm;

    protected array $missingValues = [];

    protected array $additionalValidationRules = [];

    /**
     * @var Validator
     */
    protected $validator;

    public function __construct(BaseModel $resource)
    {
        $this->setResource($resource);
    }

    public function setResource(BaseModel $resource)
    {
        $this->resource = $resource;

        $this->isCreateForm = $resource->id === null;
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function resolveValues(Collection $fields)
    {
        return $fields->map(function (Field $field) {
            if ($field->fillValue == false) {
                return $field;
            }
            $field->value = $this->resource[$field->name] ?? '';

            return $field;
        });
    }

    abstract public function fields(): Collection;

    protected function filteredFields(): Collection
    {
        return $this->fields()->filter(function (Field $field) {
            if ($this->isCreateForm === true && $field->hiddenOnCreate === true) {
                return false;
            }
            if ($this->isCreateForm === false && $field->hiddenOnUpdate === true) {
                return false;
            }

            return true;
        });
    }

    protected function getValidationRules()
    {
        return array_merge($this->fields()
            ->reduce(function ($rules, Field $field) {
                $rules[$field->name] = $field->getRules($this->isCreateForm);

                return $rules;
            }, []), $this->additionalValidationRules);
    }

    public function getValidator(): Validator
    {
        if (! $this->validator) {
            $rules = $this->getValidationRules();

            $this->validator = ValidatorFactory::make($this->data, $rules);
        }

        return $this->validator;
    }

    public function save()
    {
        throw_if($this->validator->fails(), \Exception::class, 'form not valid');

        $data = $this->afterValidation($this->validator->valid());

        if ($this->isCreateForm) {
            $this->resource = $this->resource->newQuery()->create($data);
            $this->afterCreate($this->resource);
        } else {
            $this->resource->update($data);
        }

        return $this->resource;
    }

    protected function afterValidation(array $data): array
    {
        return $data;
    }

    public function afterCreate($resource)
    {
        //
    }

    protected function removeNullValues()
    {
        $rules = $this->filteredFields()->reduce(function ($result, Field $field) {
            foreach ($field->getRules($this->isCreateForm) as $rule) {
                $result[] = $rule;
            }

            return $result;
        }, []);

        return in_array('filled', $rules);
    }

    public function toArray()
    {
        return ['data' => array_merge(
            [
                'fields' => $this->resolveValues($this->filteredFields()),
                'removeNullValues' => $this->removeNullValues(),
            ],
            ! empty($this->missingValues) ? ['missing_values' => $this->missingValues] : []
        )];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
