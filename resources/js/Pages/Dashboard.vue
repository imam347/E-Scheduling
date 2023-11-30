<script setup>
import Welcome from '@/Jetstream/Welcome.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { ref, computed, onMounted } from 'vue';

const { teachers, majors, rooms, lessons, days, times } = defineProps({
    teachers: Array,
    majors: Array,
    rooms: Array,
    lessons: Array,
    days: Array,
    times: Array,
});
// Data simulasi
const totalTeachers = ref(teachers.length); // Total guru dalam sistem
const totalMajors = ref(majors.length); // Total jurusan dalam sistem
const totalRooms = ref(rooms.length); // Total ruangan dalam sistem
const totalLessons = ref(lessons.length); // Total pelajaran dalam sistem
const totalDays = ref(days.length); // Total hari dalam sistem
const totalTimes = ref(times.length); // Total waktu dalam sistem

// onMounted(() => {
//     new Bar(document.getElementById('barChart'), {
//         data: {
//             labels: ['Total Guru', 'Total Jurusan', 'Total Ruangan', 'Total Pelajaran', 'Total Hari', 'Total Waktu'],
//             datasets: [
//                 {
//                     label: 'Jumlah',
//                     data: [totalTeachers.value, totalMajors.value, totalRooms.value, totalLessons.value, totalDays.value, totalTimes.value],
//                     backgroundColor: 'rgba(79, 209, 197, 0.7)',
//                     borderColor: 'rgba(79, 209, 197, 1)',
//                     borderWidth: 1,
//                 },
//             ],
//         },
//         options: {
//             responsive: true,
//             scales: {
//                 y: {
//                     beginAtZero: true,
//                 },
//             },
//         },
//     });
// });

</script>

<template>
    <DashboardLayout title="Dashboard">
        <div class="mt-5">
            <div class="text-center">
                <h2 class="text-xl font-semibold text-slate-300">Informasi Sistem</h2>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-3">
                <div class="bg-slate-900 p-4 rounded-md border-2 border-slate-700 shadow-md">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-chalkboard-teacher text-slate-400 mr-2 text-2xl"></i>
                        <h3 class="text-lg font-semibold text-slate-300">Total Guru</h3>
                    </div>
                    <p class="text-gray-600">{{ totalTeachers }} guru</p>
                </div>
                <div class="bg-slate-900 p-4 rounded-md border-2 border-slate-700 shadow-md">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-book-open text-slate-400 mr-2 text-2xl"></i>
                        <h3 class="text-lg font-semibold text-slate-300">Total Pelajaran</h3>
                    </div>
                    <p class="text-gray-600">{{ totalLessons }} pelajaran</p>
                </div>
                <div class="bg-slate-900 p-4 rounded-md border-2 border-slate-700 shadow-md">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-sitemap text-slate-400 mr-2 text-2xl"></i>
                        <h3 class="text-lg font-semibold text-slate-300">Total Jurusan</h3>
                    </div>
                    <p class="text-gray-600">{{ totalMajors }} Jurusan</p>
                </div>
                <div class="bg-slate-900 p-4 rounded-md border-2 border-slate-700 shadow-md">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-building-columns text-slate-400 mr-2 text-2xl"></i>
                        <h3 class="text-lg font-semibold text-slate-300">Total Ruangan </h3>
                    </div>
                    <p class="text-gray-600">{{ totalRooms }} ruangan </p>
                </div>
                <div class="bg-slate-900 p-4 rounded-md border-2 border-slate-700 shadow-md">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-calendar text-slate-400 mr-2 text-2xl"></i>
                        <h3 class="text-lg font-semibold text-slate-300">Total Hari</h3>
                    </div>
                    <p class="text-gray-600">{{ totalDays }} hari</p>
                </div>
                <div class="bg-slate-900 p-4 rounded-md border-2 border-slate-700 shadow-md">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-business-time text-slate-400 mr-2 text-2xl"></i>
                        <h3 class="text-lg font-semibold text-slate-300">Total Waktu</h3>
                    </div>
                    <p class="text-gray-600">{{ totalTimes }} waktu</p>
                </div>
            </div>
            <br>
            <!-- <br>
            <Bar :data="chartData" :options="chartOptions" />
            <br> -->
            <!-- <br>
                <div class="bg-slate-900 p-4 rounded-md border-2 border-slate-700 shadow-md">
                    <canvas id="barChart"></canvas>
                </div>
            <br> -->
            <br>
            <!-- <div class="bg-slate-900 p-4 rounded-md border-2 border-slate-700 shadow-md">
                <canvas id="barChart"></canvas>
            </div> -->
        </div>
    </DashboardLayout>
</template>
    