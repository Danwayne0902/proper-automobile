<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Reports & Analytics
        </h2>
        <div class="flex items-center space-x-4">
          <select
            v-model="selectedPeriod"
            @change="updatePeriod"
            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          >
            <option v-for="(label, value) in periods" :key="value" :value="value">
              {{ label }}
            </option>
          </select>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                  <dd class="text-lg font-medium text-gray-900">
                    ${{ formatCurrency(analytics.revenue.total) }}
                  </dd>
                </dl>
              </div>
            </div>
            <div class="mt-2">
              <div :class="analytics.revenue.growth >= 0 ? 'text-green-600' : 'text-red-600'" class="text-sm">
                {{ analytics.revenue.growth >= 0 ? '+' : '' }}{{ analytics.revenue.growth }}% from previous period
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
                  <dd class="text-lg font-medium text-gray-900">{{ analytics.bookings.total }}</dd>
                </dl>
              </div>
            </div>
            <div class="mt-2">
              <div class="text-sm text-gray-600">
                {{ analytics.bookings.conversionRate }}% conversion rate
              </div>
            </div>
          </div>

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
                  <dt class="text-sm font-medium text-gray-500 truncate">Cars Sold</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ analytics.automobiles.sold }}</dd>
                </dl>
              </div>
            </div>
            <div class="mt-2">
              <div class="text-sm text-gray-600">
                {{ analytics.automobiles.newlyAdded }} newly added
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
                  <dt class="text-sm font-medium text-gray-500 truncate">New Users</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ analytics.users.new }}</dd>
                </dl>
              </div>
            </div>
            <div class="mt-2">
              <div class="text-sm text-gray-600">
                {{ analytics.users.total }} total users
              </div>
            </div>
          </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <!-- Sales Trend Chart -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Sales Trend</h3>
            <div id="sales-trend-chart" class="h-64"></div>
          </div>

          <!-- Top Performers -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Top Performers</h3>
            <div class="space-y-4">
              <div v-for="dealer in analytics.topPerformers.dealers" :key="dealer.id" class="flex items-center justify-between">
                <div class="flex items-center">
                  <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                    <span class="text-sm font-medium text-gray-700">
                      {{ dealer.name.charAt(0).toUpperCase() }}
                    </span>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ dealer.name }}</p>
                    <p class="text-xs text-gray-500">{{ dealer.automobiles_count }} sales</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Detailed Data Tables -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-medium text-gray-900">Data Export</h3>
              <div class="text-sm text-gray-500">
                Export data for further analysis
              </div>
            </div>
          </div>

          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

              <!-- Export Automobiles -->
              <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-3">
                  <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                  <h4 class="font-medium text-gray-900">Automobiles Data</h4>
                </div>
                <p class="text-sm text-gray-600 mb-4">Export all vehicle inventory data including specifications, pricing, and dealer information.</p>

                <!-- Filters for Automobiles -->
                <div class="space-y-3 mb-4">
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status Filter</label>
                    <select v-model="exportFilters.automobiles.status" class="w-full text-sm border border-gray-300 rounded px-2 py-1">
                      <option value="">All Status</option>
                      <option value="available">Available</option>
                      <option value="sold">Sold</option>
                      <option value="reserved">Reserved</option>
                      <option value="maintenance">Maintenance</option>
                    </select>
                  </div>
                  <div class="grid grid-cols-2 gap-2">
                    <div>
                      <label class="block text-xs font-medium text-gray-700 mb-1">From Date</label>
                      <input v-model="exportFilters.automobiles.date_from" type="date" class="w-full text-sm border border-gray-300 rounded px-2 py-1" />
                    </div>
                    <div>
                      <label class="block text-xs font-medium text-gray-700 mb-1">To Date</label>
                      <input v-model="exportFilters.automobiles.date_to" type="date" class="w-full text-sm border border-gray-300 rounded px-2 py-1" />
                    </div>
                  </div>
                </div>

                <button @click="exportData('automobiles')" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm font-medium">
                  Export CSV
                </button>
              </div>

              <!-- Export Users -->
              <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-3">
                  <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                  </svg>
                  <h4 class="font-medium text-gray-900">Users Data</h4>
                </div>
                <p class="text-sm text-gray-600 mb-4">Export user accounts including customers, dealers, and admin information.</p>

                <!-- Filters for Users -->
                <div class="space-y-3 mb-4">
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Role Filter</label>
                    <select v-model="exportFilters.users.role" class="w-full text-sm border border-gray-300 rounded px-2 py-1">
                      <option value="">All Roles</option>
                      <option value="customer">Customers</option>
                      <option value="dealer">Dealers</option>
                      <option value="admin">Admins</option>
                    </select>
                  </div>
                  <div class="grid grid-cols-2 gap-2">
                    <div>
                      <label class="block text-xs font-medium text-gray-700 mb-1">From Date</label>
                      <input v-model="exportFilters.users.date_from" type="date" class="w-full text-sm border border-gray-300 rounded px-2 py-1" />
                    </div>
                    <div>
                      <label class="block text-xs font-medium text-gray-700 mb-1">To Date</label>
                      <input v-model="exportFilters.users.date_to" type="date" class="w-full text-sm border border-gray-300 rounded px-2 py-1" />
                    </div>
                  </div>
                </div>

                <button @click="exportData('users')" class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 text-sm font-medium">
                  Export CSV
                </button>
              </div>

              <!-- Export Bookings -->
              <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-3">
                  <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4l6 6m0-6l6 6"></path>
                  </svg>
                  <h4 class="font-medium text-gray-900">Bookings Data</h4>
                </div>
                <p class="text-sm text-gray-600 mb-4">Export test drive bookings and appointment information.</p>

                <!-- Filters for Bookings -->
                <div class="space-y-3 mb-4">
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status Filter</label>
                    <select v-model="exportFilters.bookings.status" class="w-full text-sm border border-gray-300 rounded px-2 py-1">
                      <option value="">All Status</option>
                      <option value="pending">Pending</option>
                      <option value="confirmed">Confirmed</option>
                      <option value="completed">Completed</option>
                      <option value="cancelled">Cancelled</option>
                    </select>
                  </div>
                  <div class="grid grid-cols-2 gap-2">
                    <div>
                      <label class="block text-xs font-medium text-gray-700 mb-1">From Date</label>
                      <input v-model="exportFilters.bookings.date_from" type="date" class="w-full text-sm border border-gray-300 rounded px-2 py-1" />
                    </div>
                    <div>
                      <label class="block text-xs font-medium text-gray-700 mb-1">To Date</label>
                      <input v-model="exportFilters.bookings.date_to" type="date" class="w-full text-sm border border-gray-300 rounded px-2 py-1" />
                    </div>
                  </div>
                </div>

                <button @click="exportData('bookings')" class="w-full bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 text-sm font-medium">
                  Export CSV
                </button>
              </div>

              <!-- Export Transactions -->
              <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-3">
                  <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <h4 class="font-medium text-gray-900">Transactions Data</h4>
                </div>
                <p class="text-sm text-gray-600 mb-4">Export financial transaction records and payment information.</p>

                <!-- Filters for Transactions -->
                <div class="space-y-3 mb-4">
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status Filter</label>
                    <select v-model="exportFilters.transactions.status" class="w-full text-sm border border-gray-300 rounded px-2 py-1">
                      <option value="">All Status</option>
                      <option value="pending">Pending</option>
                      <option value="completed">Completed</option>
                      <option value="failed">Failed</option>
                      <option value="refunded">Refunded</option>
                    </select>
                  </div>
                  <div class="grid grid-cols-2 gap-2">
                    <div>
                      <label class="block text-xs font-medium text-gray-700 mb-1">From Date</label>
                      <input v-model="exportFilters.transactions.date_from" type="date" class="w-full text-sm border border-gray-300 rounded px-2 py-1" />
                    </div>
                    <div>
                      <label class="block text-xs font-medium text-gray-700 mb-1">To Date</label>
                      <input v-model="exportFilters.transactions.date_to" type="date" class="w-full text-sm border border-gray-300 rounded px-2 py-1" />
                    </div>
                  </div>
                </div>

                <button @click="exportData('transactions')" class="w-full bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 text-sm font-medium">
                  Export CSV
                </button>
              </div>

              <!-- Sales Report -->
              <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-3">
                  <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                  </svg>
                  <h4 class="font-medium text-gray-900">Sales Report</h4>
                </div>
                <p class="text-sm text-gray-600 mb-4">Comprehensive sales report with revenue analysis and performance metrics.</p>

                <!-- Filters for Sales Report -->
                <div class="space-y-3 mb-4">
                  <div class="grid grid-cols-2 gap-2">
                    <div>
                      <label class="block text-xs font-medium text-gray-700 mb-1">From Date</label>
                      <input v-model="exportFilters.sales.date_from" type="date" class="w-full text-sm border border-gray-300 rounded px-2 py-1" />
                    </div>
                    <div>
                      <label class="block text-xs font-medium text-gray-700 mb-1">To Date</label>
                      <input v-model="exportFilters.sales.date_to" type="date" class="w-full text-sm border border-gray-300 rounded px-2 py-1" />
                    </div>
                  </div>
                </div>

                <button @click="exportSalesReport()" class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 text-sm font-medium">
                  Export Sales Report
                </button>
              </div>

              <!-- Quick Export All -->
              <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-3">
                  <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                  <h4 class="font-medium text-gray-900">Quick Export</h4>
                </div>
                <p class="text-sm text-gray-600 mb-4">Export all data types with current filter settings for comprehensive analysis.</p>

                <button @click="exportAllData()" class="w-full bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm font-medium mb-3">
                  Export All Data
                </button>

                <div class="text-xs text-gray-500">
                  <p>This will generate separate CSV files for:</p>
                  <ul class="list-disc list-inside mt-1">
                    <li>Automobiles</li>
                    <li>Users</li>
                    <li>Bookings</li>
                    <li>Transactions</li>
                  </ul>
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
import AppLayout from '@/Components/Layout/AppLayout.vue'
import { Inertia } from '@inertiajs/inertia'
// Import ApexCharts
import ApexCharts from 'apexcharts'

export default {
  name: 'AdminReportsIndex',

  components: {
    AppLayout
  },

  props: {
    analytics: {
      type: Object,
      required: true
    },
    period: {
      type: String,
      default: '30'
    }
  },

  data() {
    return {
      selectedPeriod: this.period,
      periods: {
        '7': 'Last 7 Days',
        '30': 'Last 30 Days',
        '90': 'Last 90 Days',
        '365': 'Last Year'
      },
      exportFilters: {
        automobiles: {
          status: '',
          date_from: '',
          date_to: ''
        },
        users: {
          role: '',
          date_from: '',
          date_to: ''
        },
        bookings: {
          status: '',
          date_from: '',
          date_to: ''
        },
        transactions: {
          status: '',
          date_from: '',
          date_to: ''
        },
        sales: {
          date_from: '',
          date_to: ''
        }
      },
      salesTrendChart: null
    }
  },

  mounted() {
    this.drawSalesTrendChart()
  },

  beforeDestroy() {
    // Clean up charts to prevent memory leaks
    if (this.salesTrendChart) {
      this.salesTrendChart.destroy()
    }
  },

  methods: {
    updatePeriod() {
      Inertia.get(route('admin.reports.index'), { period: this.selectedPeriod }, {
        preserveState: true
      })
    },

    drawSalesTrendChart() {
      const chartElement = document.getElementById('sales-trend-chart')
      if (!chartElement) return

      // Prepare dynamic data from analytics
      const salesData = this.analytics.salesTrends.map(item => item.revenue)
      const dates = this.analytics.salesTrends.map(item => {
        const date = new Date(item.date)
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
      })

      const options = {
        chart: {
          type: 'area',
          height: 350,
          toolbar: {
            show: false
          }
        },
        series: [{
          name: 'Revenue',
          data: salesData
        }],
        xaxis: {
          categories: dates
        },
        stroke: {
          curve: 'smooth'
        },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.9,
            stops: [0, 90, 100]
          }
        },
        dataLabels: {
          enabled: false
        },
        tooltip: {
          x: {
            format: 'dd/MM/yy'
          }
        }
      }

      // Destroy existing chart if it exists
      if (this.salesTrendChart) {
        this.salesTrendChart.destroy()
      }

      this.salesTrendChart = new ApexCharts(chartElement, options)
      this.salesTrendChart.render()
    },

    formatCurrency(amount) {
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
      }).format(amount)
    },

    exportData(type) {
      // Implementation for exporting data
      alert(`Exporting ${type} data...`)
    },

    exportSalesReport() {
      // Implementation for exporting sales report
      alert('Exporting sales report...')
    },

    exportAllData() {
      // Implementation for exporting all data
      alert('Exporting all data...')
    }
  }
}
</script>
