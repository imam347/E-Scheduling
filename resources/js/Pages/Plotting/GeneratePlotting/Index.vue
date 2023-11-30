<script setup>
import { getCurrentInstance, nextTick, onMounted, onUnmounted, ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Card from "@/Components/Card.vue";
import Icon from "@/Components/Icon.vue";
import Builder from "@/Components/DataTable/Builder.vue";
import Th from "@/Components/DataTable/Th.vue";
import Swal from "sweetalert2";
import Select from "@vueform/multiselect";
import Modal from "@/Components/Modal.vue";
import Close from "@/Components/Button/Close.vue";
import ButtonGreen from "@/Components/Button/Green.vue";
import ButtonBlue from "@/Components/Button/Blue.vue";
import ButtonRed from "@/Components/Button/Red.vue";
import Input from "@/Components/Input.vue";
import InputError from "@/Components/InputError.vue";

const self = getCurrentInstance();
const { teachers, majors, rooms, lessons, days, times, schedule } = defineProps({
    teachers: Array,
    majors: Array,
    rooms: Array,
    lessons: Array,
    days: Array,
    times: Array,
    schedule: Array
});

const render = ref(true);

const form = useForm({
    schedule: schedule,
});

const form_preferensi = useForm({
    id: null,
    teacher_id: null,
    day_id: []
});

const table = ref(null);
const open = ref(false);
const openPreferensi = ref(false);
const disabled = ref(false);
const filteredRoom = ref([]);

const show = () => (open.value = true);
const showPreferensi = () => (openPreferensi.value = true);

const close = () => {
    open.value = false;
    openPreferensi.value = false;
    form.reset();
    form_preferensi.reset();
    render.value = false;
    nextTick(() => (render.value = true));
    disabled.value = false;
};

const selectRoom = (value) => {
    filteredRoom.value = rooms.filter(r => r.major_id === value)
}

const detach = async (employees, permission) => {
    const { isConfirmed } = await Swal.fire({
        title: __("are you sure") + "?",
        icon: "question",
        showCloseButton: true,
        showCancelButton: true,
    });

    if (!isConfirmed) {
        return;
    }

    Inertia.on("finish", () => close());
    Inertia.patch(
        route("superuser.positions.detach", {
            division: division.id,
            permission: permission.id,
        })
    );
};

const store = async () => {
    // Swal.fire({
    //     title: 'Loading...',
    //     toast: true,
    //     position: 'top-end',
    //     allowOutsideClick: false,
    //     allowEscapeKey: false,
    //     allowEnterKey: false,
    //     showConfirmButton: false,
    //     didOpen: () => {
    //         Swal.showLoading()
    //     }
    // })

    // const response = await axios.post(route("generate_schedule"), form)
    //     .then(response => {
    //         if (response.data?.message) {
    //             console.log(response);
    //             return
    //         }
    //         Swal.close()
    //         alert('test');

    //         // training.value = response.data
    //         // presences.value = response.data.presences

    //         // form.training_id = training.value.id

    //         // err.value = null
    //         // token.value = null
    //     })
    //     .catch(error => {
    //         console.log(error)
    //     })
    //     .finally(response => {
    //         Swal.close()
    //     })

    const response = await Swal.fire({
        title: __("Apakah anda yakin?") + "?",
        text: __("apakah anda yakin akan menyimpan jadwal pelajaran ini?"),
        icon: "question",
        showCancelButton: true,
        showCloseButton: true,
    });

    if (response.isConfirmed) {
        Inertia.on("finish", () => close());
        return form.post(route('generate_schedule.save'), {
            onSuccess: () => close(),
            onError: () => show(),
        })
    }
};

const storePreferensi = () => {
    // console.log(form_preferensi)
    return form_preferensi.post(route("plotting.store_preferensi"), {
        onSuccess: () => close(),
        onError: () => showPreferensi(),
    });
};

const edit = (plotting) => {
    show();
};

const update = () => {
    return form.patch(route("employee_skill.update", form.id), {
        onSuccess: () => close(),
        onError: () => show(),
    });
};

const destroy = async (plotting) => {
    const response = await Swal.fire({
        title: __("are you sure") + "?",
        text: __("you can't restore it after deleted"),
        icon: "question",
        showCancelButton: true,
        showCloseButton: true,
    });

    if (response.isConfirmed) {
        Inertia.on("finish", () => close());

        return Inertia.delete(route("employee_skill.destroy", plotting.id));
    }
};

const submit = () => store();

const submitPreferensiMengajar = () => (storePreferensi());


const esc = (e) => e.key === "Escape" && close();
onMounted(() => window.addEventListener("keydown", esc));
onUnmounted(() => window.removeEventListener("keydown", esc));
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>

<template>
    <DashboardLayout :title="__('plotting')">
        <Card class="bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
            <template #header>
                <div class="flex items-center space-x-2 p-2 bg-gray-200 dark:bg-gray-800">
                    <!-- <form @submit.prevent="submit" class="w-full max-w-xl grid-cols-4">
                        <div class="flex items-center py-2">
                            <Select v-model="form.code_room_id" :options="rooms.map((t) => ({
                                label: __(t.name),
                                value: t.id,
                            }))
                                " :searchable="true" :clearOnSelect="false" :closeOnSelect="false"
                                :placeholder="__('pilih kelas')" class="uppercase px-2" :createOption="true"
                                :disabled="form.id ? true : false" required="required" />
                            <div class="w-2"></div>
                            <ButtonBlue class="py-2" type="submit">
                                <Icon name="print" />
                                <p class="uppercase font-semibold">
                                    {{ __("generate") }}
                                </p>
                            </ButtonBlue>
                            <div class="w-2"></div> -->
                    <ButtonBlue @click.prevent="
                        form_preferensi.id = null;
                    store();
                    ">
                        <Icon name="plus" />
                        <p class="uppercase font-semibold">
                            {{ __("simpan") }}
                        </p>
                    </ButtonBlue>

                    <!-- </div>
                    </form> -->
                </div>
            </template>

            <template #body>
            </template>
        </Card>
        <!-- <div> -->
        <Card class="flex flex-col-2 space-y-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 mt-8 p-2"
            v-for="(day) in days" :key="day.id">
            <template #header>
                <h1 class="text-center text-[24px] font-bold">{{ day.name }}</h1>
            </template>
            <template #body>

                <div class="flex flex-col space-y-2 p-3">
                    <table border="0">
                        <tr class="border">
                            <td class="border text-center font-bold text-[20px]">{{ ('Jam') }}</td>
                            <td class="border text-center font-bold text-[20px]">{{ ('Pelajaran') }}</td>
                            <td class="border text-center font-bold text-[20px]">{{ ('Guru') }}</td>
                        </tr>
                        <tr class="border text-center" v-for="(time) in times" :key="time.id">
                            <td class="border">{{ (time.range_min) }} - {{ (time.range_max) }}</td>
                            <td class="border" v-if="schedule.find(s => (s.day === day.id && s.time == time.id))">{{
                                lessons.find(l => l.id == (schedule.find(s => (s.day === day.id && s.time ==
                                    time.id)).lesson)).name }}</td>
                            <td v-else class="border">{{ ('-') }}</td>

                            <td class="border" v-if="schedule.find(s => (s.day === day.id && s.time == time.id))">{{
                                teachers.find(t => t.id == (schedule.find(s => (s.day === day.id && s.time ==
                                    time.id)).teacher)).name }}</td>
                            <td v-else class="border">{{ ('-') }}</td>
                        </tr>
                    </table>
                </div>
            </template>
            <template #footer></template>

        </Card>
    <!-- </div> -->
</DashboardLayout></template>  