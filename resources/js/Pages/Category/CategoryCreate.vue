<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create Category
            </h2>
        </template>

        <div>
            <div class="mx-auto py-10 sm:px-6 lg:px-8">
                <jet-form-section @submitted="createCategory">
                    <template #title>
                        Category
                    </template>

                    <template #description>
                        Create a brand new Category
                    </template>

                    <template #form>
                        <!-- Name -->
                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="name" value="Name" />
                            <jet-input
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="category.name"
                                autocomplete="name"
                            />
                        </div>
                        <!-- Attribute -->
                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="attributes" value="Attributes" />
                            <v-select
                                id="attributes"
                                v-model="category.attributes"
                                :options="attributeOptions"
                                :reduce="a => a.value"
                                taggable
                                multiple
                            />
                        </div>
                    </template>

                    <template #actions>
                        <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                            Saved.
                        </jet-action-message>

                        <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Save
                        </jet-button>
                    </template>
                </jet-form-section>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetButton from '@/Jetstream/Button'
import JetFormSection from '@/Jetstream/FormSection'
import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'
import JetLabel from '@/Jetstream/Label'
import JetActionMessage from '@/Jetstream/ActionMessage'

import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'

export default {
    components: {
        AppLayout,
        JetActionMessage,
        JetButton,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        vSelect
    },

    props: ['attributes'],

    data() {
        return {
            category: {}
        }
    },

    computed: {
        attributeOptions() {
            return this.attributes.map(a => ({ label: a.name, value: a.id }))
        },
        form() {
            return this.$inertia.form(
                {
                    _method: 'POST',
                    name: this.category.name,
                    has_attributes: this.category.attributes
                },
                {
                    bag: 'createCategory',
                    resetOnSuccess: false
                }
            )
        }
    },

    methods: {
        createCategory() {
            this.form.post(route('categories.store'), {
                preserveScroll: true
            })
        }
    }
}
</script>
