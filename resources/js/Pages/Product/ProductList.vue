<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Products
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div class="mx-4 my-4">
                    <inertia-link :href="route('products.create')">
                        <jet-button>
                            Add
                        </jet-button>
                    </inertia-link>
                </div>
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Name
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Attributes
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Warranty
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Category
                                        </th>
                                        <th scope="col" class="px-6 py-3 bg-gray-50">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="product in products" :key="product.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ product.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{
                                                product.attributes
                                                    .map(a => a.name + ': ' + a.pivot.value)
                                                    .join(', ')
                                            }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ product.warranty ? product.warranty.name : 'no warranty' }}
                                            {{
                                                product.warranty
                                                    ? ': ' + product.warranty.duration_months + ' months'
                                                    : ''
                                            }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ product.category.name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                        >
                                            <inertia-link
                                                :href="route('products.edit', product.id)"
                                                class="mx-4"
                                            >
                                                <jet-button>
                                                    Edit
                                                </jet-button>
                                            </inertia-link>
                                            <jet-danger-button @click.native="deleteProduct(product.id)">
                                                Delete
                                            </jet-danger-button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetSectionBorder from '@/Jetstream/SectionBorder'
import JetButton from '@/Jetstream/Button'
import JetDangerButton from '@/Jetstream/DangerButton'

export default {
    props: ['products'],

    components: {
        AppLayout,
        JetSectionBorder,
        JetButton,
        JetDangerButton
    },

    methods: {
        deleteProduct(id) {
            this.$inertia
                .form({
                    _method: 'DELETE'
                })
                .post(route('products.destroy', id))
        }
    }
}
</script>
