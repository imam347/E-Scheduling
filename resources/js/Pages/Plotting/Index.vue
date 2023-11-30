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
const { teachers, majors, rooms, lessons, days, schedule } = defineProps({
    teachers: Array,
    majors: Array,
    rooms: Array,
    lessons: Array,
    days: Array,
    schedule: Array,
});

const render = ref(true);

const form = useForm({
    id: null,
    teacher_id: null,
    lesson_id: null,
    code_major_id: null,
    code_room_id: null
});

const form_preferensi = useForm({
    id: null,
    teacher_id: null,
    day_id: []
});

const form_generate = useForm({
    id: null,
    code_room_id: null
});

const table = ref(null);
const open = ref(false);
const openPreferensi = ref(false);
const openGenerate = ref(false);

const disabled = ref(false);
const filteredRoom = ref([]);

const show = () => (open.value = true);
const showPreferensi = () => (openPreferensi.value = true);
const showGenerate = () => (openGenerate.value = true);


const close = () => {
    open.value = false;
    openPreferensi.value = false;
    openGenerate.value = false;
    form.reset();
    form_preferensi.reset();
    form_generate.reset();
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

const store = () => {
    return form.post(route("plotting.store"), {
        onSuccess: () => close(),
        onError: () => show(),
    });
};

const storePreferensi = () => {
    console.log(form_preferensi)
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

const submit = () => (form.id ? update() : store());

const submitPreferensiMengajar = () => (storePreferensi());


const esc = (e) => e.key === "Escape" && close();
onMounted(() => window.addEventListener("keydown", esc));
onUnmounted(() => window.removeEventListener("keydown", esc));
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>

<template>
    <DashboardLayout :title="__('plotting')">
        <Card class="flex flex-col-2 space-y-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 mt-8 p-2">
            <template #header>
                <div class="flex items-center space-x-2 p-2 bg-gray-200 dark:bg-gray-800">
                    <ButtonGreen @click.prevent="
                        form.id = null;
                    show();
                    ">
                        <Icon name="plus" />
                        <p class="uppercase font-semibold">
                            {{ __("Input plotting mengajar") }}
                        </p>
                    </ButtonGreen>

                    <ButtonGreen @click.prevent="
                        form_preferensi.id = null;
                    showPreferensi();
                    ">
                        <Icon name="plus" />
                        <p class="uppercase font-semibold">
                            {{ __("Input preferensi mengajar") }}
                        </p>
                    </ButtonGreen>

                    <a target="_blank" :href="route('print_schedule')">
                    <ButtonRed>
                        <Icon name="print" />
                                <p class="uppercase font-semibold">
                                    {{ __("Print") }}
                                </p>
                </ButtonRed>
                    </a>
                </div>
                <!-- <h1 class="text-center text-[24px] font-bold">{{ day.name }}</h1> -->
            </template>
            <template #body>

                <div class="flex flex-col space-y-2 p-3">
                    <table border="0">
                        <tr class="border">
                            <td class="border text-center font-bold text-[20px]">{{ ('Kelas') }}</td>
                            <td class="border text-center font-bold text-[20px]">{{ ('Aksi') }}</td>
                        </tr>
                        <tr class="border text-center" v-for="(room) in rooms" :key="room.id">
                            <td class="border">{{ (room.name) }}</td>
                            <td>
                                
                        <a target="_blank"
                            :href="route('generate_schedule', room.id)">
                        <div class="flex items-center justify-end space-x-2 bg-gray-200 dark:bg-gray-800 px-2 py-1">
                            <ButtonBlue>
                                <Icon name="print" />
                                <p class="uppercase font-semibold">
                                    {{ __(disabled ? "update" : "Generate Jadwal") }}
                                </p>
                            </ButtonBlue>
                    <!-- <a :href="route('print_schedule')" target="_blank">
                    <ButtonRed class="border" v-if="schedule.find(r => (r.code_room_id === room.id))">
                        <Icon name="print" />
                                <p class="uppercase font-semibold">
                                    {{ __("Print") }}
                                </p>
                </ButtonRed>
                    </a> -->
                        </div>
                    </a>
                            </td>
                            
                        </tr>
                    </table>
                </div>
            </template>
            <template #footer></template>

        </Card>
        <Card class="bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
            <template #header>
            </template>

            <template #body>
                <div class="flex flex-col space-y-2">
                    <Builder v-if="render" :url="route('plotting.paginate')" ref="table">
                        <template #thead="table">
                            <tr class="bg-gray-200 dark:bg-gray-800 border-gray-300 dark:border-gray-900">
                                <Th :table="table" :sort="false" class="border px-3 py-2 text-center">
                                    {{ __("no") }}
                                </Th>

                                <Th :table="table" :sort="true" name="division"
                                    class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __("Guru") }}
                                </Th>

                                <Th :table="table" :sort="true" name="division"
                                    class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __("Jurusan") }}
                                </Th>

                                <Th :table="table" :sort="true" name="division"
                                    class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __("Ruang Kelas") }}
                                </Th>

                                <Th :table="table" :sort="true" name="division"
                                    class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __("Pelajaran") }}
                                </Th>

                                <Th :table="table" :sort="true" name="division"
                                    class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __("Hari") }}
                                </Th>

                                <Th :table="table" :sort="true" name="division"
                                    class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __("Jam") }}
                                </Th>

                                <Th :table="table" :sort="false" class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __("#") }}
                                </Th>
                            </tr>
                        </template>

                        <template #tfoot="table">
                            <tr class="bg-gray-200 dark:bg-gray-800 border-gray-300 dark:border-gray-900">
                                <Th :table="table" :sort="false" class="border px-3 py-2 text-center">
                                    {{ __("no") }}
                                </Th>

                                <Th :table="table" :sort="true" name="division"
                                    class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __("Guru") }}
                                </Th>

                                <Th :table="table" :sort="true" name="division"
                                    class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __("Jurusan") }}
                                </Th>

                                <Th :table="table" :sort="true" name="division"
                                    class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __("Ruang Kelas") }}
                                </Th>

                                <Th :table="table" :sort="true" name="division"
                                    class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __("Pelajaran") }}
                                </Th>

                                <Th :table="table" :sort="true" name="division"
                                    class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __("Hari") }}
                                </Th>

                                <Th :table="table" :sort="true" name="division"
                                    class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __("Jam") }}
                                </Th>

                                <Th :table="table" :sort="false" class="border px-3 py-2 text-center whitespace-nowrap">
                                    {{ __("#") }}
                                </Th>
                            </tr>
                        </template>

                        <template #tbody="{ data, processing, empty }">
                            <TransitionGroup enterActiveClass="transition-all duration-200"
                                leaveActiveClass="transition-all duration-200" enterFromClass="opacity-0 -scale-y-100"
                                leaveToClass="opacity-0 -scale-y-100">
                                <template v-if="empty">
                                    <tr>
                                        <td class="text-5xl text-center p-4" colspan="1000">
                                            <p class="lowercase first-letter:capitalize font-semibold">
                                                {{ __("Tidak ada data yang di tampilkan") }}
                                            </p>
                                        </td>
                                    </tr>
                                </template>

                                <template v-else>
                                    <tr v-for="(plotting, i) in data" :key="plotting.id"
                                        :class="processing && 'bg-gray-800'"
                                        class="dark:hover:bg-gray-600 transition-all duration-300">
                                        <td class="px-2 py-1 border dark:border-gray-800 text-center">
                                            {{ i + 1 }}
                                        </td>

                                        <td v-if="plotting.teacher" class="px-2 py-1 border dark:border-gray-800 uppercase">
                                            {{ __(plotting.teacher.name) }}
                                        </td>

                                        <td v-else class="px-2 py-1 border dark:border-gray-800 uppercase">
                                            {{ __('Tidak Ada') }}
                                        </td>

                                        <td v-if="plotting.room" class="px-2 py-1 border dark:border-gray-800 uppercase">
                                            {{ __(plotting.room.major.name) }}
                                        </td>

                                        <td v-else class="px-2 py-1 border dark:border-gray-800 uppercase">
                                            {{ __('Tidak Ada') }}
                                        </td>

                                        <td v-if="plotting.room" class="px-2 py-1 border dark:border-gray-800 uppercase">
                                            {{ __(plotting.room.name) }}
                                        </td>

                                        <td v-else class="px-2 py-1 border dark:border-gray-800 uppercase">
                                            {{ __('Tidak Ada') }}
                                        </td>

                                        <td v-if="plotting.lesson" class="px-2 py-1 border dark:border-gray-800 uppercase">
                                            {{ __(plotting.lesson.name) }}
                                        </td>

                                        <td v-else class="px-2 py-1 border dark:border-gray-800 uppercase">
                                            {{ __('Tidak Ada') }}
                                        </td>

                                        <td v-if="plotting.day" class="px-2 py-1 border dark:border-gray-800 uppercase">
                                            {{ __(plotting.day.name) }}
                                        </td>

                                        <td v-else class="px-2 py-1 border dark:border-gray-800 uppercase">
                                            {{ __('Tidak Ada') }}
                                        </td>

                                        <td v-if="plotting.time" class="px-2 py-1 border dark:border-gray-800 uppercase">
                                            {{ __(plotting.time.range) }}
                                        </td>

                                        <td v-else class="px-2 py-1 border dark:border-gray-800 uppercase">
                                            {{ __('Tidak Ada') }}
                                        </td>

                                        <td class="px-2 py-1 border dark:border-gray-800">
                                        </td>
                                    </tr>
                                </template>
                            </TransitionGroup>
                        </template>
                    </Builder>
                </div>
            </template>
        </Card>

        <!-- Modal Input Plotting Mengajar -->
        <Modal :show="open">
            <form @submit.prevent="submit" class="w-full max-w-xl h-fit shadow-xl">
                <Card class="bg-gray-50 dark:bg-gray-700 dark:text-gray-100 w-full max-w-xl">
                    <template #header>
                        <div class="flex items-center justify-end bg-gray-200 dark:bg-gray-800 p-2">
                            <Close @click.prevent="close" />
                        </div>
                    </template>

                    <template #body>
                        <div class="flex flex-col space-y-4 p-4">

                            <div class="flex flex-col space-y-2">
                                <div class="flex items-center space-x-2">
                                    <label for="division" class="w-1/3 lowercase first-letter:capitalize">
                                        {{ __("nama guru") }}
                                    </label>

                                    <Select v-model="form.teacher_id" :options="teachers.map((t) => ({
                                        label: __(t.name),
                                        value: t.id,
                                    }))
                                        " :searchable="true" :clearOnSelect="false" :closeOnSelect="false"
                                        :placeholder="__('pilih guru')" class="uppercase" :createOption="true"
                                        @option="addTag" :disabled="form.id ? true : false" required="required" />
                                </div>

                                <InputError :error="form.errors.division" />
                            </div>
                        </div>
                        <div class="flex flex-col space-y-4 p-4">

                            <div class="flex flex-col space-y-2">
                                <div class="flex items-center space-x-2">
                                    <label for="division" class="w-1/3 lowercase first-letter:capitalize">
                                        {{ __("jurusan") }}
                                    </label>

                                    <Select v-model="form.code_major_id" :options="majors.map((t) => ({
                                        label: __(t.name),
                                        value: t.id,
                                    }))
                                        " :searchable="true" :clearOnSelect="false" :closeOnSelect="false"
                                        :placeholder="__('pilih jurusan')" class="uppercase" :createOption="true"
                                        @option="addTag" required="required" @select="selectRoom" />
                                </div>

                                <InputError :error="form.errors.division" />
                            </div>
                        </div>

                        <div class="flex flex-col space-y-4 p-4">

                            <div class="flex flex-col space-y-2">
                                <div class="flex items-center space-x-2">
                                    <label for="division" class="w-1/3 lowercase first-letter:capitalize">
                                        {{ __("kelas") }}
                                    </label>

                                    <Select v-model="form.code_room_id" :options="filteredRoom.map((t) => ({
                                        label: __(t.name),
                                        value: t.id,
                                    }))
                                        " :searchable="true" :clearOnSelect="false" :closeOnSelect="false"
                                        :placeholder="__('pilih kelas')" class="uppercase" :createOption="true"
                                        @option="addTag" :disabled="form.id ? true : false" required="required" />
                                </div>

                                <InputError :error="form.errors.division" />
                            </div>
                        </div>

                        <div class="flex flex-col space-y-4 p-4">

                            <div class="flex flex-col space-y-2">
                                <div class="flex items-center space-x-2">
                                    <label for="division" class="w-1/3 lowercase first-letter:capitalize">
                                        {{ __("mata pelajaran") }}
                                    </label>

                                    <Select v-model="form.lesson_id" :options="lessons.map((t) => ({
                                        label: __(t.name),
                                        value: t.id,
                                    }))
                                        " :searchable="true" :clearOnSelect="false" :closeOnSelect="false"
                                        :placeholder="__('pilih mata pelajaran')" class="uppercase" :createOption="true"
                                        @option="addTag" :disabled="form.id ? true : false" required="required" />
                                </div>

                                <InputError :error="form.errors.division" />
                            </div>
                        </div>
                    </template>

                    <template #footer>
                        <div class="flex items-center justify-end space-x-2 bg-gray-200 dark:bg-gray-800 px-2 py-1">
                            <ButtonGreen type="submit">
                                <Icon name="check" />
                                <p class="uppercase font-semibold">
                                    {{ __(disabled ? "update" : "create") }}
                                </p>
                            </ButtonGreen>
                        </div>
                    </template>
                </Card>
            </form>
        </Modal>

        <!-- Modal Preferensi Mengajar -->
        <Modal :show="openPreferensi">
            <form @submit.prevent="submitPreferensiMengajar" class="w-full max-w-xl h-fit shadow-xl">
                <Card class="bg-gray-50 dark:bg-gray-700 dark:text-gray-100 w-full max-w-xl">
                    <template #header>
                        <div class="flex items-center justify-end bg-gray-200 dark:bg-gray-800 p-2">
                            <Close @click.prevent="close" />
                        </div>
                    </template>

                    <template #body>
                        <div class="flex flex-col space-y-4 p-4">

                            <div class="flex flex-col space-y-2">
                                <div class="flex items-center space-x-2">
                                    <label for="division" class="w-1/3 lowercase first-letter:capitalize">
                                        {{ __("nama guru") }}
                                    </label>

                                    <Select v-model="form_preferensi.teacher_id" :options="teachers.map((t) => ({
                                        label: __(t.name),
                                        value: t.id,
                                    }))
                                        " :searchable="true" :clearOnSelect="false" :closeOnSelect="false"
                                        :placeholder="__('pilih guru')" class="uppercase" :createOption="true"
                                        :disabled="form.id ? true : false" required="required" />
                                </div>

                                <InputError :error="form.errors.division" />
                            </div>
                        </div>
                        <div class="flex flex-col space-y-4 p-4">

                            <div class="flex flex-col space-y-2">
                                <div class="flex items-center space-x-2">
                                    <label for="division" class="w-1/3 lowercase first-letter:capitalize">
                                        {{ __("Hari Mengajar") }}
                                    </label>

                                    <Select v-model="form_preferensi
                                        .day_id" :options="days.map((d) => ({
        label: __(d.name),
        value: d.id,
    }))
        " :searchable="true" :clearOnSelect="false" :closeOnSelect="false" :placeholder="__('pilih hari')"
                                        class="uppercase" :createOption="true" mode="tags" required="required" />
                                </div>

                                <InputError :error="form.errors.division" />
                            </div>
                        </div>
                    </template>

                    <template #footer>
                        <div class="flex items-center justify-end space-x-2 bg-gray-200 dark:bg-gray-800 px-2 py-1">
                            <ButtonGreen type="submit">
                                <Icon name="check" />
                                <p class="uppercase font-semibold">
                                    {{ __(disabled ? "update" : "create") }}
                                </p>
                            </ButtonGreen>
                        </div>
                    </template>
                </Card>
            </form>
        </Modal>

        <Modal :show="openGenerate">
            <form class="w-full max-w-xl h-fit shadow-xl">
                <Card class="bg-gray-50 dark:bg-gray-700 dark:text-gray-100 w-full max-w-xl">
                    <template #header>
                        <div class="flex items-center justify-end bg-gray-200 dark:bg-gray-800 p-2">
                            <Close @click.prevent="close" />
                        </div>
                    </template>

                    <template #body>
                        <div class="flex flex-col space-y-4 p-4">

                            <div class="flex flex-col space-y-2">
                                <div class="flex items-center space-x-2">
                                    <label for="division" class="w-1/3 lowercase first-letter:capitalize">
                                        {{ __("pilih kelas") }}
                                    </label>

                                    <Select v-model="form_generate.code_room_id" :options="rooms.map((t) => ({
                                        label: __(t.name),
                                        value: t.id,
                                    }))
                                        " :searchable="true" :placeholder="__('pilih kelas')" class="uppercase px-2"
                                        :createOption="true" :disabled="form.id ? true : false" required="required" />
                                </div>

                                <InputError :error="form_generate.errors.code_room_id" />
                            </div>
                        </div>
                    </template>

                    <template #footer>
                        <a target="_blank" v-if="form_generate.code_room_id"
                            :href="route('generate_schedule', form_generate.code_room_id)">
                        <div class="flex items-center justify-end space-x-2 bg-gray-200 dark:bg-gray-800 px-2 py-1">
                            <ButtonGreen>
                                <Icon name="check" />
                                <p class="uppercase font-semibold">
                                    {{ __(disabled ? "update" : "Generate Jadwal") }}
                                </p>
                            </ButtonGreen>
                        </div>
                    </a>
                    </template>
                </Card>
            </form>
        </Modal>

    </DashboardLayout>
</template>  