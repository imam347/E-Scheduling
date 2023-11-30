<script setup>
import { getCurrentInstance, nextTick, onMounted, onUnmounted, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { useForm } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Icon from '@/Components/Icon.vue'
import Builder from '@/Components/DataTable/Builder.vue'
import Th from '@/Components/DataTable/Th.vue'
import Swal from 'sweetalert2'
import Select from '@vueform/multiselect'
import Modal from '@/Components/Modal.vue'
import ButtonGreen from '@/Components/Button/Green.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import ButtonRed from '@/Components/Button/Red.vue'
import Close from '@/Components/Button/Close.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'

const self = getCurrentInstance()
const render = ref(true)
// const { permissions, roles } = defineProps({
//   permissions: Array,
//   roles: Array,
// })

const form = useForm({
  id: null,
  name: '',
  code: '',
  sks: '',
})

const table = ref(null)
const open = ref(false)

const show = () => open.value = true

const close = () => {
  open.value = false
  form.reset()
  render.value = false
  nextTick(() => render.value = true)
}

const store = () => {
  return form.post(route('lesson.store'), {
    onSuccess: () => close(),
    onError: () => show(),
  })
}

const edit = lesson => {
  form.id = lesson.id
  form.name = lesson.name
  form.code = lesson.code
  form.sks = lesson.sks
  show()
}

const update = () => {
  return form.patch(route('lesson.update', form.id), {
    onSuccess: () => close(),
    onError: () => show(),
  })
}

const destroy = async lesson => {
  const response = await Swal.fire({
    title: __('are you sure') + '?',
    text: __('you can\'t restore it after deleted'),
    icon: 'question',
    showCancelButton: true,
    showCloseButton: true,
  })

  if (response.isConfirmed) {
    Inertia.on('finish', () => close())

    return Inertia.delete(route('lesson.destroy', lesson.id))
  }
}

const submit = () => form.id ? update() : store()

// const detachPermission = async (lesson, permission) => {
//   const response = await Swal.fire({
//     title: __('are you sure') + '?',
//     text: __('you can re adding it on edit page'),
//     icon: 'question',
//     showCancelButton: true,
//     showCloseButton: true,
//   })

//   if (!response.isConfirmed)
//     return

//   Inertia.on('finish', () => close())
//   Inertia.patch(route('supermajor.lesson.permission.detach', { lesson: lesson.id, permission: permission.id }))
// }

// const detachRole = async (lesson, role) => {
//   const response = await Swal.fire({
//     title: __('are you sure') + '?',
//     text: __('you can re adding it on edit page'),
//     icon: 'question',
//     showCancelButton: true,
//     showCloseButton: true,
//   })

//   if (!response.isConfirmed)
//     return

//   Inertia.on('finish', () => close())
//   Inertia.patch(route('supermajor.lesson.role.detach', { lesson: lesson.id, role: role.id }))
// }

const esc = e => e.key === 'Escape' && close()

onMounted(() => window.addEventListener('keydown', esc))
onUnmounted(() => window.removeEventListener('keydown', esc))
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>

<template>

  <DashboardLayout
    :title="__('Mata Pelajaran')"
  >
    <Card class="bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
      <template #header>
        <div class="flex items-center space-x-2 p-2 bg-gray-200 dark:bg-gray-800">
          <ButtonGreen
            v-if="can('create lesson')"
            @click.prevent="form.id = null; show()"
          >
            <Icon name="plus" />
            <p class="uppercase font-semibold">
              {{ __('create') }}
            </p>
          </ButtonGreen>
        </div>
      </template>

      <template #body>
        <div class="flex flex-col space-y-2">
          <Builder
            v-if="render"
            :url="route('lesson.paginate')"
            ref="table"
          >
            <template #thead="table">
              <tr class="bg-gray-200 dark:bg-gray-800 border-gray-300 dark:border-gray-900">
                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center"
                >
                  {{ __('no') }}
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="name"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  {{ __('name') }}
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="code"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  {{ __('code') }}
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="sks"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  {{ __('sks') }}
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="created_at"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  {{ __('created at') }}
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="updated_at"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  {{ __('updated at') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  {{ __('action') }}
                </Th>
              </tr>
            </template>

            <template #tfoot="table">
              <tr class="bg-gray-200 dark:bg-gray-800 border-gray-300 dark:border-gray-900">
                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center"
                >
                  {{ __('no') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  {{ __('name') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  {{ __('code') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  {{ __('sks') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  {{ __('created at') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  {{ __('updated at') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  {{ __('action') }}
                </Th>
              </tr>
            </template>

            <template #tbody="{ data, processing, empty }">
              <TransitionGroup
                enterActiveClass="transition-all duration-200"
                leaveActiveClass="transition-all duration-200"
                enterFromClass="opacity-0 -scale-y-100"
                leaveToClass="opacity-0 -scale-y-100"
              >
                <template v-if="empty">
                  <tr>
                    <td class="text-5xl text-center p-4" colspan="1000">
                      <p class="lowercase first-letter:capitalize font-semibold">
                        {{ __('Tidak ada data yang di tampilkan') }}
                      </p>
                    </td>
                  </tr>
                </template>

                <template v-else>
                  <tr
                    v-for="(lesson, i) in data"
                    :key="i"
                    :class="processing && 'bg-gray-800'"
                    class="dark:hover:bg-gray-600 transition-all duration-300"
                  >
                    <td class="px-2 py-1 border dark:border-gray-800 text-center">
                      {{ i + 1 }}
                    </td>

                    <td class="px-2 py-1 border dark:border-gray-800 uppercase">
                      {{ lesson.name }}
                    </td>

                    <td class="px-2 py-1 border dark:border-gray-800 uppercase">
                      {{ lesson.code }}
                    </td>

                    <td class="px-2 py-1 border dark:border-gray-800 uppercase">
                      {{ lesson.sks }}
                    </td>

                    <td class="px-2 py-1 border dark:border-gray-800 uppercase">
                      {{ new Date(lesson.created_at).toLocaleString('id') }}
                    </td>

                    <td class="px-2 py-1 border dark:border-gray-800 uppercase">
                      {{ new Date(lesson.updated_at).toLocaleString('id') }}
                    </td>

                    <td class="px-2 py-1 border dark:border-gray-800">
                      <div class="flex items-center space-x-2">
                        <ButtonBlue
                          v-if="can('update lesson')"
                          @click.prevent="edit(lesson)"
                        >
                          <Icon name="edit" />
                          <p class="uppercase">
                            {{ __('edit') }}
                          </p>
                        </ButtonBlue>

                        <ButtonRed
                          v-if="can('delete lesson')"
                          @click.prevent="destroy(lesson)"
                        >
                          <Icon name="trash" />
                          <p class="uppercase">
                            {{ __('delete') }}
                          </p>
                        </ButtonRed>
                      </div>
                    </td>
                  </tr>
                </template>
              </TransitionGroup>
            </template>
          </Builder>
        </div>
      </template>
    </Card>

    <Modal :show="open">
      <form
        @submit.prevent="submit"
        class="w-full max-w-xl sm:max-w-5xl h-fit shadow-xl"
      >
        <Card class="bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
          <template #header>
            <div class="flex items-center justify-end bg-gray-200 dark:bg-gray-800 p-2">
              <Close @click.prevent="close" />
            </div>
          </template>

          <template #body>
            <div class="flex flex-col space-y-4 p-4">
              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="name" class="w-1/3 lowercase first-letter:capitalize">
                    {{ __('name') }}
                  </label>

                  <Input
                    v-model="form.name"
                    :placeholder="__('name')"
                    type="text"
                    name="name"
                    required
                    autofocus
                  />
                </div>

                <InputError :error="form.errors.name" />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="code" class="w-1/3 lowercase first-letter:capitalize">
                    {{ __('code') }}
                  </label>
                  
                  <Input
                    v-model="form.code"
                    :placeholder="__('code')"
                    type="number"
                    name="code"
                    required
                  />
                </div>

                <InputError :error="form.errors.code" />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="sks" class="w-1/3 lowercase first-letter:capitalize">
                    {{ __('sks') }}
                  </label>
                  
                  <Input
                    v-model="form.sks"
                    :placeholder="__('sks')"
                    type="number"
                    name="sks"
                    required
                  />
                </div>

                <InputError :error="form.errors.sks" />
              </div>
            </div>
          </template>

          <template #footer>
            <div class="flex items-center justify-end space-x-2 bg-gray-200 dark:bg-gray-800 px-2 py-1">
              <ButtonGreen type="submit">
                <Icon name="check" />
                <p class="uppercase font-semibold">
                  {{ __(form.id ? 'update' : 'create') }}
                </p>
              </ButtonGreen>
            </div>
          </template>
        </Card>
      </form>
    </Modal>
  </DashboardLayout>
</template>