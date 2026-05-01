<x-app-layout>
    <div class="py-12" x-data="dashboardChart()" x-init="fetchData()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4 border-b pb-4">
                    {{ __('Dashboard: รายรับ-รายจ่าย') }}
                </h2>

                <!-- Filters -->
                <div class="flex flex-col md:flex-row gap-4 mb-6">
                    <template x-if="isSuperUser">
                        <div class="flex flex-col">
                            <label class="text-sm text-gray-600 dark:text-gray-400 mb-1">ผู้ใช้งาน</label>
                            <select x-model="userId" @change="fetchData()" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="all">ทั้งหมด (All Users)</option>
                                <template x-for="u in usersList" :key="u.id">
                                    <option :value="u.id" x-text="u.name"></option>
                                </template>
                            </select>
                        </div>
                    </template>

                    <div class="flex flex-col">
                        <label class="text-sm text-gray-600 dark:text-gray-400 mb-1">เริ่มจากวันที่</label>
                        <input type="date" x-model="startDate" @change="fetchData()" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-600 dark:text-gray-400 mb-1">ถึงวันที่</label>
                        <input type="date" x-model="endDate" @change="fetchData()" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="p-4 rounded-lg bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800">
                        <h3 class="text-green-800 dark:text-green-300 text-sm font-bold uppercase">ยอดรายรับรวม</h3>
                        <p class="text-2xl text-green-600 dark:text-green-400 font-extrabold mt-1" x-text="formatCurrency(totalIncome)"></p>
                    </div>
                    <div class="p-4 rounded-lg bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-800">
                        <h3 class="text-red-800 dark:text-red-300 text-sm font-bold uppercase">ยอดรายจ่ายรวม</h3>
                        <p class="text-2xl text-red-600 dark:text-red-400 font-extrabold mt-1" x-text="formatCurrency(totalExpense)"></p>
                    </div>
                </div>

                <!-- Chart -->
                <div class="relative w-full h-96">
                    <canvas id="ledgerChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Load Chart.js via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function dashboardChart() {
            return {
                isSuperUser: false,
                usersList: [],
                userId: 'all',
                startDate: '',
                endDate: '',
                totalIncome: 0,
                totalExpense: 0,
                chartInstance: null,

                formatCurrency(value) {
                    return new Intl.NumberFormat('th-TH', { style: 'currency', currency: 'THB' }).format(value);
                },

                fetchData() {
                    let params = new URLSearchParams();
                    if (this.userId) params.append('user_id', this.userId);
                    if (this.startDate) params.append('start_date', this.startDate);
                    if (this.endDate) params.append('end_date', this.endDate);

                    axios.get(`/dashboard/data?${params.toString()}`)
                        .then(response => {
                            const data = response.data;
                            this.isSuperUser = data.is_super_user;
                            this.usersList = data.users;
                            this.totalIncome = data.total_income;
                            this.totalExpense = data.total_expense;

                            this.renderChart(data.labels, data.incomes, data.expenses);
                        })
                        .catch(error => {
                            console.error("Error fetching dashboard data:", error);
                        });
                },

                renderChart(labels, incomes, expenses) {
                    const ctx = document.getElementById('ledgerChart');
                    
                    if (this.chartInstance) {
                        this.chartInstance.destroy();
                    }

                    this.chartInstance = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'รายรับ (Income)',
                                    data: incomes,
                                    backgroundColor: 'rgba(34, 197, 94, 0.5)',
                                    borderColor: 'rgb(34, 197, 94)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'รายจ่าย (Expense)',
                                    data: expenses,
                                    backgroundColor: 'rgba(239, 68, 68, 0.5)',
                                    borderColor: 'rgb(239, 68, 68)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    title: { color: 'gray' },
                                    labels: {
                                         color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#4b5563'
                                    },
                                    grid: {
                                        color: document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#4b5563'
                                    },
                                    grid: {
                                        color: document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                                    }
                                }
                            }
                        }
                    });
                }
            }
        }
    </script>
</x-app-layout>
