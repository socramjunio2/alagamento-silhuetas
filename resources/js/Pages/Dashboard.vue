<template>
    <breeze-authenticated-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        Bem vindo ao teste de silhuetas alagadas.
                    </div>
                    <form @submit.prevent="this.form.post('/send-file')" class="p-6 bg-white border-b border-gray-200" enctype="multipart/form-data">
                        <breeze-validation-errors class="mb-4" />

                        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                            {{ status }}
                        </div>

                        <div class="mt-4">
                            <breeze-label for="silhouette" value="File" />
                            <breeze-textarea id="silhouette" class="mt-1 block w-full" v-model="form.silhouette" required autocomplete="send-file" />
                        </div>

                        <div class="mt-4">
                            <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                {{ form.progress.percentage }}%
                            </progress>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="/assets/case_demo.txt" target="_blank" class="underline text-sm text-gray-600 hover:text-gray-900">
                                Baixar caso demo
                            </a>

                            <breeze-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Enviar casos
                            </breeze-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </breeze-authenticated-layout>
</template>

<script>
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
    import BreezeValidationErrors from '@/Components/ValidationErrors'
    import BreezeButton from '@/Components/Button'
    import BreezeCheckbox from "@/Components/Checkbox";
    import BreezeGuestLayout from "@/Layouts/Guest"
    import BreezeInput from '@/Components/Input'
    import BreezeTextarea from '@/Components/Textarea'
    import BreezeLabel from '@/Components/Label'

    export default {
        data() {
            return {
                form: this.$inertia.form({
                    silhouette: '7\n' +
                        '9\n' +
                        '5 4 3 2 1 2 3 4 5\n' +
                        '30\n' +
                        '7 10 2 5 13 3 4 10 5 9 4 2 6 5 18 6 8 6 15 4 20 4 8 9 5 21 4 7 19 2\n' +
                        '1\n' +
                        '10\n' +
                        '10\n' +
                        '1 2 3 4 5 6 7 8 9 10\n' +
                        '10\n' +
                        '10 9 8 7 6 5 4 3 2 1\n' +
                        '15\n' +
                        '10 10 10 10 10 10 10 10 10 10 10 10 10 10 10\n' +
                        '20\n' +
                        '1 2 3 4 5 6 7 8 9 10 10 9 8 7 6 5 4 3 2 1\n'
                })
            }
        },
        components: {
            BreezeAuthenticatedLayout,
            BreezeButton,
            BreezeCheckbox,
            BreezeInput,
            BreezeTextarea,
            BreezeLabel,
            BreezeValidationErrors,
        }
    }
</script>
