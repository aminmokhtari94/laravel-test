<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Categories
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div
                    class="px-4 py-3 leading-normal text-green-700 bg-green-100 rounded-lg"
                    role="alert"
                    v-if="$page.flash.message"
                >
                    <p class="font-bold">{{ $page.flash.message }}</p>
                </div>
                <div class="mx-4 my-4">
                    <inertia-link :href="route('categories.create')">
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
                                            Has Warranty
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Updated At
                                        </th>
                                        <th scope="col" class="px-6 py-3 bg-gray-50">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="category in categories" :key="category.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ category.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ category.attributes.map(a => a.name).join(', ') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ category.has_warranty ? 'yes' : 'no' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ category.updated_at }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                        >
                                            <inertia-link
                                                :href="route('categories.edit', category.id)"
                                                class="mx-4"
                                            >
                                                <jet-button>
                                                    Edit
                                                </jet-button>
                                            </inertia-link>
                                            <jet-danger-button @click.native="deleteCategory(category.id)">
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
    props: ['categories'],

    components: {
        AppLayout,
        JetSectionBorder,
        JetButton,
        JetDangerButton
    },

    methods: {
        deleteCategory(id) {
            this.$inertia
                .form({
                    _method: 'DELETE'
                })
                .post(route('categories.destroy', id))
        }
    }
}
</script>
