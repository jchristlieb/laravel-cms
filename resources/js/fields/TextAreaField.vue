<template>
    <field-wrapper :name="field.name" :label="field.label || field.name" :errors="validationErrors">
        <textarea class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                  :class="{'border-red-600': !!hasValidationErrors}"
                  :id="field.name"
                  :name="field.name"
                  :rows="field.rows || 8"
                  v-model="value">
        </textarea>
    </field-wrapper>
</template>
<script>
    import FormField from "../lib/mixins/FormField";

    export default {
        mixins: [FormField]
    }
</script>
