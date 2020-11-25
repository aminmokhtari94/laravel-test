<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Product {{ product.id }}</h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <jet-form-section @submitted="editProduct">
                    <template #title>
                        Product
                    </template>

                    <template #description>
                        Edit your product
                    </template>

                    <template #form>
                        <!-- Name -->
                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="name" value="Name" />
                            <jet-input
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                autocomplete="name"
                            />
                        </div>
                        <!-- Category -->
                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="category" value="Category" />
                            <v-select
                                id="category"
                                v-model="form.category_id"
                                :options="categoryOptions"
                                :reduce="a => a.value"
                            />
                        </div>
                        <!-- Attribute Value -->
                        <template v-if="form.category_id">
                            <div
                                class="col-span-6 sm:col-span-4"
                                v-for="attr in categories.find(cat => cat.id === form.category_id).attributes"
                                :key="attr.id"
                            >
                                <jet-label for="attribute_value" :value="`${attr.name}`" />
                                <jet-input
                                    id="attribute_value"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.attribute_values[attr.id]"
                                    autocomplete="name"
                                />
                            </div>
                            <!-- Warranty -->
                            <template v-if="categories.find(c => c.id === form.category_id).has_warranty">
                                <div class="col-span-6 sm:col-span-4">
                                    <jet-label for="warranty_name" value="Warranty Name" />
                                    <jet-input
                                        id="warranty_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.warranty_name"
                                        autocomplete="name"
                                    />
                                </div>
                                <div class="col-span-6 sm:col-span-4">
                                    <jet-label for="warranty_months" value="Warranty Months" />
                                    <jet-input
                                        id="warranty_months"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.warranty_months"
                                        autocomplete="name"
                                    />
                                </div>
                            </template>
                        </template>
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

    props: ['product', 'categories'],

    data() {
        const avs = {}
        Object.entries(this.product.attributes).forEach(av => {
            const [key, value] = av
            avs[value.id] = value.pivot.value
        })
        return {
            form: this.$inertia.form(
                {
                    _method: 'PUT',
                    name: this.product.name,
                    category_id: this.product.category_id,
                    attribute_values: avs,
                    warranty_name: this.product.warranty ? this.product.warranty.name : '',
                    warranty_months: this.product.warranty ? this.product.warranty.duration_months : ''
                },
                {
                    bag: 'editProduct',
                    resetOnSuccess: false
                }
            )
        }
    },

    watch: {
        'form.category_id'() {
            this.form.attribute_values = {}
        }
    },

    computed: {
        categoryOptions() {
            return this.categories.map(a => ({ label: a.name, value: a.id }))
        }
    },

    methods: {
        editProduct() {
            const avs = {}
            Object.entries(this.form.attribute_values).forEach(av => {
                const [key, value] = av
                avs[key] = { value }
            })
            this.form.attribute_values = avs
            this.form.post(route('products.update', this.product.id), {
                preserveScroll: true
            })
        }
    }
}
</script>
