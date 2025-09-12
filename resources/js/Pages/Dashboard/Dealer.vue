<template>
  <AppLayout title="Dealer Dashboard">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Dealer Dashboard
        </h2>
        <div class="flex items-center space-x-2">
          <label for="period" class="text-sm text-gray-700">Time Period:</label>
          <select
            id="period"
            v-model="selectedPeriod"
            @change="updatePeriod"
            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
          >
            <option value="7">Last 7 days</option>
            <option value="30">Last 30 days</option>
            <option value="90">Last 90 days</option>
            <option value="365">Last year</option>
          </select>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                  <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                  </svg>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">{{ stats.my_automobiles }}</h3>
                  <p class="text-sm text-gray-500">My Automobiles</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                  <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">{{ stats.available_automobiles }}</h3>
                  <p class="text-sm text-gray-500">Available</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                  <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">{{ stats.my_bookings }}</h3>
                  <p class="text-sm text-gray-500">Total Bookings</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                  <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                  </svg>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">{{ formatCurrency(stats.my_revenue) }}</h3>
                  <p class="text-sm text-gray-500">Total Revenue</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                  <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">{{ stats.pending_payments }}</h3>
                  <p class="text-sm text-gray-500">
                    <Link :href="route('reports.payments')" class="text-indigo-600 hover:text-indigo-900">
                      Pending Payments
                    </Link>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
          <!-- Sales Chart -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Daily Sales ({{ selectedPeriodText }})</h3>
              <div class="h-64">
                <canvas ref="salesChart"></canvas>
              </div>
            </div>
          </div>

          <!-- Top Performing Automobiles -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Top Performing Automobiles</h3>
              <div class="space-y-4">
                <div v-for="(automobile, index) in topAutomobiles" :key="automobile.id" class="flex items-center justify-between border-b border-gray-200 pb-3 last:border-0 last:pb-0">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-sm font-bold text-gray-700">
                      {{ index + 1 }}
                    </div>
                    <div class="ml-3">
                      <p class="text-sm font-medium text-gray-900">{{ automobile.year }} {{ automobile.make }} {{ automobile.model }}</p>
                      <p class="text-xs text-gray-500">{{ formatCurrency(automobile.total_revenue || 0) }} • {{ automobile.transaction_count || 0 }} sales</p>
                    </div>
                  </div>
                  <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">{{ formatCurrency(automobile.total_revenue || 0) }}</p>
                  </div>
                </div>
                <div v-if="topAutomobiles.length === 0" class="text-center py-4 text-gray-500">
                  No sales data available
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Recent Bookings -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Bookings</h3>
              <div class="space-y-4">
                <div v-for="booking in recentBookings" :key="booking.id" class="flex items-center justify-between border-b border-gray-200 pb-3 last:border-0 last:pb-0">
                  <div>
                    <p class="text-sm font-medium text-gray-900">{{ booking.booking_number }}</p>
                    <p class="text-xs text-gray-500">{{ booking.user.name }} • {{ formatDate(booking.scheduled_at) }}</p>
                  </div>
                  <div class="text-right">
                    <span :class="statusClass(booking.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                      {{ booking.status.charAt(0).toUpperCase() + booking.status.slice(1) }}
                    </span>
                    <p class="text-xs text-gray-500 mt-1">{{ booking.automobile.year }} {{ booking.automobile.make }}</p>
                  </div>
                </div>
                <div v-if="recentBookings.length === 0" class="text-center py-4 text-gray-500">
                  No recent bookings
                </div>
              </div>
            </div>
          </div>

          <!-- Recent Transactions -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Transactions</h3>
              <div class="space-y-4">
                <div v-for="transaction in recentTransactions" :key="transaction.id" class="flex items-center justify-between border-b border-gray-200 pb-3 last:border-0 last:pb-0">
                  <div>
                    <p class="text-sm font-medium text-gray-900">{{ transaction.transaction_number }}</p>
                    <p class="text-xs text-gray-500">{{ transaction.user.name }} • {{ formatDate(transaction.created_at) }}</p>
                  </div>
                  <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">{{ formatCurrency(transaction.amount) }}</p>
                    <span :class="statusClass(transaction.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                      {{ transaction.status.charAt(0).toUpperCase() + transaction.status.slice(1) }}
                    </span>
                  </div>
                </div>
                <div v-if="recentTransactions.length === 0" class="text-center py-4 text-gray-500">
                  No recent transactions
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue'
import AppLayout from '@/Components/Layout/AppLayout.vue'
import {
  Chart,
  CategoryScale,
  LinearScale,
  BarElement,
  LineElement,
  PointElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js'
import { Inertia } from '@inertiajs/inertia'

Chart.register(
  CategoryScale,
  LinearScale,
  BarElement,
  LineElement,
  PointElement,
  Title,
  Tooltip,
  Legend
)

export default {
  name: 'DealerDashboard',

  components: {
    AppLayout,
    Link
  },

  props: {
    stats: {
      type: Object,
      required: true
    },
    salesData: {
      type: Array,
      default: () => []
    },
    topAutomobiles: {
      type: Array,
      default: () => []
    },
    recentBookings: {
      type: Array,
      default: () => []
    },
    recentTransactions: {
      type: Array,
      default: () => []
    },
    period: {
      type: String,
      default: '30'
    }
  },

  data() {
    return {
      selectedPeriod: this.period,
      salesChart: null
    }
  },

  computed: {
    selectedPeriodText() {
      const periods = {
        '7': 'Last 7 days',
        '30': 'Last 30 days',
        '90': 'Last 90 days',
        '365': 'Last year'
      }
      return periods[this.selectedPeriod] || 'Last 30 days'
    }
  },

  mounted() {
    this.initializeCharts()
  },

  beforeDestroy() {
    // Clean up charts to prevent memory leaks
    if (this.salesChart) this.salesChart.destroy()
  },

  methods: {
    updatePeriod() {
      Inertia.get(route('dashboard'), { period: this.selectedPeriod }, {
        preserveState: true,
        onSuccess: () => {
          this.$nextTick(() => {
            this.initializeCharts()
          })
        }
      })
    },

    initializeCharts() {
      // Destroy existing charts if they exist
      if (this.salesChart) this.salesChart.destroy()

      // Create new charts
      this.drawSalesChart()
    },

    drawSalesChart() {
      const canvas = this.$refs.salesChart
      if (!canvas) return

      const ctx = canvas.getContext('2d')

      // Prepare data for the chart
      const dates = this.salesData.map(item => new Date(item.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }))
      const amounts = this.salesData.map(item => item.total)

      this.salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: dates,
          datasets: [{
            label: 'Daily Sales',
            data: amounts,
            backgroundColor: 'rgba(59, 130, 246, 0.5)',
            borderColor: 'rgba(59, 130, 246, 1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            },
            title: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  return '$' + value.toLocaleString()
                }
              }
            }
          }
        }
      })
    },

    formatCurrency(amount) {
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
      }).format(amount || 0)
    },

    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric'
      })
    },

    statusClass(status) {
      const classes = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'confirmed': 'bg-green-100 text-green-800',
        'completed': 'bg-blue-100 text-blue-800',
        'cancelled': 'bg-red-100 text-red-800',
        'processing': 'bg-blue-100 text-blue-800',
        'failed': 'bg-red-100 text-red-800',
        'refunded': 'bg-gray-100 text-gray-800'
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    }
  }
}
</script>
