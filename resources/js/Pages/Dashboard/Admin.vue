<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Admin Dashboard
        </h2>
        <div class="flex items-center space-x-4">
          <select
            v-model="selectedPeriod"
            @change="updatePeriod"
            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
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

        <!-- Overview Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Automobiles</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ stats.total_automobiles }}</dd>
                </dl>
              </div>
            </div>
            <div class="mt-2">
              <div class="text-sm text-gray-600">
                {{ stats.available_automobiles }} available
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2 2H4zm2 6a2 2 0 012-2h8a1 1 0 100-2H4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                  <dd class="text-lg font-medium text-gray-900">${{ formatCurrency(stats.total_revenue) }}</dd>
                </dl>
              </div>
            </div>
            <div class="mt-2">
              <div class="text-sm text-gray-600">
                {{ stats.total_transactions }} transactions
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Bookings</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ stats.total_bookings }}</dd>
                </dl>
              </div>
            </div>
            <div class="mt-2">
              <div class="text-sm text-gray-600">
                {{ stats.pending_bookings }} pending
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ stats.total_users }}</dd>
                </dl>
              </div>
            </div>
            <div class="mt-2">
              <div class="text-sm text-gray-600">
                {{ stats.total_dealers }} dealers
              </div>
            </div>
          </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

          <!-- Sales Chart -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Daily Sales ({{ selectedPeriodText }})</h3>
            <div class="h-64">
              <canvas ref="salesChart"></canvas>
            </div>
          </div>

          <!-- Revenue Trend -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Revenue Trend</h3>
            <div class="h-64">
              <canvas ref="revenueChart"></canvas>
            </div>
          </div>
        </div>

        <!-- Analytics Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

          <!-- Booking Status Distribution -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Booking Status</h3>
            <div class="h-48">
              <canvas ref="bookingChart"></canvas>
            </div>
          </div>

          <!-- Transaction Types -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Transaction Types</h3>
            <div class="h-48">
              <canvas ref="transactionChart"></canvas>
            </div>
          </div>

          <!-- Top Automobiles -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Top Performing Automobiles</h3>
            <div class="space-y-3">
              <div
                v-for="auto in topAutomobiles.slice(0, 5)"
                :key="auto.id"
                class="flex items-center justify-between"
              >
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 truncate">
                    {{ auto.year || 'N/A' }} {{ auto.make || 'Unknown' }} {{ auto.model || 'Model' }}
                  </p>
                  <p class="text-sm text-gray-500">
                    {{ auto.transaction_count || 0 }} transactions
                  </p>
                </div>
                <div class="ml-2 text-sm font-medium text-green-600">
                  ${{ formatCurrency(auto.total_revenue || 0) }}
                </div>
              </div>
              <div v-if="topAutomobiles.length === 0" class="text-center text-gray-500 py-4">
                No data available
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

          <!-- Recent Bookings -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">Recent Bookings</h3>
            </div>
            <div class="divide-y divide-gray-200">
              <div
                v-for="booking in recentBookings"
                :key="booking.id"
                class="px-6 py-4"
              >
                <div class="flex items-center justify-between">
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">
                      {{ booking.booking_number || 'N/A' }}
                    </p>
                    <p class="text-sm text-gray-500 truncate">
                      {{ (booking.user && booking.user.name) ? booking.user.name : 'Unknown User' }} -
                      {{ (booking.automobile && booking.automobile.year) ? booking.automobile.year : 'N/A' }}
                      {{ (booking.automobile && booking.automobile.make) ? booking.automobile.make : 'Unknown' }}
                      {{ (booking.automobile && booking.automobile.model) ? booking.automobile.model : 'Model' }}
                    </p>
                  </div>
                  <div class="ml-2 flex-shrink-0">
                    <span
                      :class="statusClass(booking.status)"
                      class="px-2 py-1 text-xs font-semibold rounded-full"
                    >
                      {{ booking.status || 'N/A' }}
                    </span>
                  </div>
                </div>
                <p class="mt-1 text-xs text-gray-500">
                  {{ formatDate(booking.created_at) }}
                </p>
              </div>
              <div v-if="recentBookings.length === 0" class="px-6 py-4 text-center text-gray-500">
                No recent bookings
              </div>
            </div>
          </div>

          <!-- Recent Transactions -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">Recent Transactions</h3>
            </div>
            <div class="divide-y divide-gray-200">
              <div
                v-for="transaction in recentTransactions"
                :key="transaction.id"
                class="px-6 py-4"
              >
                <div class="flex items-center justify-between">
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">
                      {{ transaction.transaction_number }}
                    </p>
                    <p class="text-sm text-gray-500 truncate">
                      {{ transaction.user.name }} - ${{ formatCurrency(transaction.amount) }}
                    </p>
                  </div>
                  <div class="ml-2 flex-shrink-0">
                    <span
                      :class="statusClass(transaction.status)"
                      class="px-2 py-1 text-xs font-semibold rounded-full"
                    >
                      {{ transaction.status }}
                    </span>
                  </div>
                </div>
                <p class="mt-1 text-xs text-gray-500">
                  {{ formatDate(transaction.created_at) }}
                </p>
              </div>
              <div v-if="recentTransactions.length === 0" class="px-6 py-4 text-center text-gray-500">
                No recent transactions
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import AppLayout from '@/Components/Layout/AppLayout.vue'
import { Inertia } from '@inertiajs/inertia'
import {
  Chart,
  LineController,
  BarController,
  PieController,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  ArcElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js'

// Register Chart.js components
Chart.register(
  LineController,
  BarController,
  PieController,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  ArcElement,
  Title,
  Tooltip,
  Legend
)

export default {
  name: 'AdminDashboard',

  components: {
    AppLayout
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
    bookingStats: {
      type: [Object, Array],
      default: () => ({})
    },
    transactionStats: {
      type: [Object, Array],
      default: () => ({})
    },
    recentBookings: {
      type: Array,
      default: () => []
    },
    recentTransactions: {
      type: Array,
      default: () => []
    },
    monthlyRevenue: {
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
      salesChart: null,
      revenueChart: null,
      bookingChart: null,
      transactionChart: null
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
    if (this.revenueChart) this.revenueChart.destroy()
    if (this.bookingChart) this.bookingChart.destroy()
    if (this.transactionChart) this.transactionChart.destroy()
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
      if (this.revenueChart) this.revenueChart.destroy()
      if (this.bookingChart) this.bookingChart.destroy()
      if (this.transactionChart) this.transactionChart.destroy()

      // Create new charts
      this.drawSalesChart()
      this.drawRevenueChart()
      this.drawBookingChart()
      this.drawTransactionChart()
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

    drawRevenueChart() {
      const canvas = this.$refs.revenueChart
      if (!canvas || !this.monthlyRevenue || this.monthlyRevenue.length === 0) return

      const ctx = canvas.getContext('2d')

      // Prepare data for the chart, with null checking
      const validData = this.monthlyRevenue.filter(item => item && item.year && item.month);

      if (validData.length === 0) return;

      const months = validData.map(item => {
        const date = new Date(item.year, item.month - 1)
        return date.toLocaleDateString('en-US', { month: 'short', year: '2-digit' })
      })
      const amounts = validData.map(item => item.total || 0)

      this.revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: months,
          datasets: [{
            label: 'Monthly Revenue',
            data: amounts,
            fill: false,
            borderColor: 'rgb(16, 185, 129)',
            tension: 0.1,
            pointBackgroundColor: 'rgb(16, 185, 129)'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
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

    drawBookingChart() {
      const canvas = this.$refs.bookingChart
      if (!canvas) return

      const ctx = canvas.getContext('2d')

      // Prepare data for the chart
      const statuses = Object.keys(this.bookingStats)
      const counts = Object.values(this.bookingStats)
      const backgroundColors = [
        'rgba(245, 158, 11, 0.8)', // pending - yellow
        'rgba(16, 185, 129, 0.8)', // approved - green
        'rgba(59, 130, 246, 0.8)', // completed - blue
        'rgba(239, 68, 68, 0.8)'   // cancelled - red
      ]

      this.bookingChart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: statuses.map(status => status.charAt(0).toUpperCase() + status.slice(1)),
          datasets: [{
            data: counts,
            backgroundColor: backgroundColors.slice(0, statuses.length)
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'right'
            }
          }
        }
      })
    },

    drawTransactionChart() {
      const canvas = this.$refs.transactionChart
      if (!canvas) return

      const ctx = canvas.getContext('2d')

      // Prepare data for the chart
      const types = Object.keys(this.transactionStats)
      const amounts = Object.values(this.transactionStats)
      const backgroundColors = [
        'rgba(139, 92, 246, 0.8)', // purchase - purple
        'rgba(236, 72, 153, 0.8)'  // service - pink
      ]

      this.transactionChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: types.map(type => type.charAt(0).toUpperCase() + type.slice(1)),
          datasets: [{
            data: amounts,
            backgroundColor: backgroundColors.slice(0, types.length)
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'right'
            }
          }
        }
      })
    },

    formatCurrency(amount) {
      if (amount === null || amount === undefined) return '0';
      return new Intl.NumberFormat('en-US').format(amount)
    },

    formatDate(date) {
      if (!date) return 'N/A';
      const d = new Date(date);
      if (isNaN(d.getTime())) return 'Invalid Date';
      return d.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
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
