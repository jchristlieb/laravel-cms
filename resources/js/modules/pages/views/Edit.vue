<template>
    <div>

        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h1 class="text-lg leading-6 font-medium text-gray-900">
                    {{ $t('pages.edit_title') }}
                </h1>
            </div>
        </div>
        <ResourceForm
            resource="page"
            :resource-id="id"
            @cancel="$router.push({name: 'pages.index'})"
            @success="handleSuccess"
        />
    </div>
</template>

<script>
    import ResourceForm from "../../../components/ResourceForm";

    export default {
        components: {ResourceForm},

        props: ['id'],

        methods: {
            handleSuccess(page) {
                Cms.flash('success', 'Nice one!');
                this.$router.push({name: 'pages.show', params: {id: page.id}})
            }
        }
    }
</script>

