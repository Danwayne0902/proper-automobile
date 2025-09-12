<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Edit {{ automobile.year }} {{ automobile.make }} {{ automobile.model }}
        </h2>
        <div class="flex space-x-2">
          <Link
            :href="route('automobiles.show', automobile.id)"
            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
          >
            Cancel
          </Link>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <form @submit.prevent="submit" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Basic Information -->
              <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
              </div>

              <!-- Make -->
              <div>
                <label for="make" class="block text-sm font-medium text-gray-700 mb-1">
                  Make <span class="text-red-500">*</span>
                </label>
                <input
                  id="make"
                  v-model="form.make"
                  type="text"
                  required
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.make }"
                />
                <p v-if="errors.make" class="mt-1 text-sm text-red-600">{{ errors.make }}</p>
              </div>

              <!-- Model -->
              <div>
                <label for="model" class="block text-sm font-medium text-gray-700 mb-1">
                  Model <span class="text-red-500">*</span>
                </label>
                <input
                  id="model"
                  v-model="form.model"
                  type="text"
                  required
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.model }"
                />
                <p v-if="errors.model" class="mt-1 text-sm text-red-600">{{ errors.model }}</p>
              </div>

              <!-- Year -->
              <div>
                <label for="year" class="block text-sm font-medium text-gray-700 mb-1">
                  Year <span class="text-red-500">*</span>
                </label>
                <input
                  id="year"
                  v-model="form.year"
                  type="number"
                  :min="1900"
                  :max="new Date().getFullYear() + 1"
                  required
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.year }"
                />
                <p v-if="errors.year" class="mt-1 text-sm text-red-600">{{ errors.year }}</p>
              </div>

              <!-- VIN -->
              <div>
                <label for="vin" class="block text-sm font-medium text-gray-700 mb-1">
                  VIN <span class="text-red-500">*</span>
                </label>
                <input
                  id="vin"
                  v-model="form.vin"
                  type="text"
                  maxlength="17"
                  required
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.vin }"
                />
                <p v-if="errors.vin" class="mt-1 text-sm text-red-600">{{ errors.vin }}</p>
              </div>

              <!-- Price -->
              <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                  Price <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">$</span>
                  </div>
                  <input
                    id="price"
                    v-model="form.price"
                    type="number"
                    step="0.01"
                    min="0"
                    required
                    class="block w-full pl-7 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    :class="{ 'border-red-300': errors.price }"
                  />
                </div>
                <p v-if="errors.price" class="mt-1 text-sm text-red-600">{{ errors.price }}</p>
              </div>

              <!-- Mileage -->
              <div>
                <label for="mileage" class="block text-sm font-medium text-gray-700 mb-1">
                  Mileage <span class="text-red-500">*</span>
                </label>
                <input
                  id="mileage"
                  v-model="form.mileage"
                  type="number"
                  min="0"
                  required
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.mileage }"
                />
                <p v-if="errors.mileage" class="mt-1 text-sm text-red-600">{{ errors.mileage }}</p>
              </div>

              <!-- Specifications -->
              <div class="md:col-span-2 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Specifications</h3>
              </div>

              <!-- Color -->
              <div>
                <label for="color" class="block text-sm font-medium text-gray-700 mb-1">
                  Color <span class="text-red-500">*</span>
                </label>
                <input
                  id="color"
                  v-model="form.color"
                  type="text"
                  required
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.color }"
                />
                <p v-if="errors.color" class="mt-1 text-sm text-red-600">{{ errors.color }}</p>
              </div>

              <!-- Body Type -->
              <div>
                <label for="body_type" class="block text-sm font-medium text-gray-700 mb-1">
                  Body Type <span class="text-red-500">*</span>
                </label>
                <select
                  id="body_type"
                  v-model="form.body_type"
                  required
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.body_type }"
                >
                  <option value="">Select Body Type</option>
                  <option value="Sedan">Sedan</option>
                  <option value="SUV">SUV</option>
                  <option value="Hatchback">Hatchback</option>
                  <option value="Coupe">Coupe</option>
                  <option value="Convertible">Convertible</option>
                  <option value="Wagon">Wagon</option>
                  <option value="Pickup">Pickup</option>
                  <option value="Van">Van</option>
                  <option value="Other">Other</option>
                </select>
                <p v-if="errors.body_type" class="mt-1 text-sm text-red-600">{{ errors.body_type }}</p>
              </div>

              <!-- Fuel Type -->
              <div>
                <label for="fuel_type" class="block text-sm font-medium text-gray-700 mb-1">
                  Fuel Type <span class="text-red-500">*</span>
                </label>
                <select
                  id="fuel_type"
                  v-model="form.fuel_type"
                  required
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.fuel_type }"
                >
                  <option value="">Select Fuel Type</option>
                  <option value="Gasoline">Gasoline</option>
                  <option value="Diesel">Diesel</option>
                  <option value="Electric">Electric</option>
                  <option value="Hybrid">Hybrid</option>
                  <option value="Plug-in Hybrid">Plug-in Hybrid</option>
                  <option value="CNG">CNG</option>
                  <option value="LPG">LPG</option>
                </select>
                <p v-if="errors.fuel_type" class="mt-1 text-sm text-red-600">{{ errors.fuel_type }}</p>
              </div>

              <!-- Transmission -->
              <div>
                <label for="transmission" class="block text-sm font-medium text-gray-700 mb-1">
                  Transmission <span class="text-red-500">*</span>
                </label>
                <select
                  id="transmission"
                  v-model="form.transmission"
                  required
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.transmission }"
                >
                  <option value="">Select Transmission</option>
                  <option value="Manual">Manual</option>
                  <option value="Automatic">Automatic</option>
                  <option value="CVT">CVT</option>
                  <option value="Semi-Automatic">Semi-Automatic</option>
                </select>
                <p v-if="errors.transmission" class="mt-1 text-sm text-red-600">{{ errors.transmission }}</p>
              </div>

              <!-- Engine Size -->
              <div>
                <label for="engine_size" class="block text-sm font-medium text-gray-700 mb-1">
                  Engine Size
                </label>
                <input
                  id="engine_size"
                  v-model="form.engine_size"
                  type="text"
                  placeholder="e.g., 2.0L, 1.8L Turbo"
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.engine_size }"
                />
                <p v-if="errors.engine_size" class="mt-1 text-sm text-red-600">{{ errors.engine_size }}</p>
              </div>

              <!-- Status -->
              <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                  Status <span class="text-red-500">*</span>
                </label>
                <select
                  id="status"
                  v-model="form.status"
                  required
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.status }"
                >
                  <option value="">Select Status</option>
                  <option value="available">Available</option>
                  <option value="sold">Sold</option>
                  <option value="reserved">Reserved</option>
                  <option value="maintenance">Maintenance</option>
                </select>
                <p v-if="errors.status" class="mt-1 text-sm text-red-600">{{ errors.status }}</p>
              </div>

              <!-- Description -->
              <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                  Description
                </label>
                <textarea
                  id="description"
                  v-model="form.description"
                  rows="4"
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.description }"
                  placeholder="Describe the automobile's condition, features, history, etc."
                ></textarea>
                <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</p>
              </div>

              <!-- Images Management -->
              <div class="md:col-span-2 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Images</h3>

                <!-- Existing Images -->
                <div v-if="existingImages.length > 0" class="mb-4">
                  <h4 class="text-md font-medium text-gray-700 mb-2">Current Images</h4>
                  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    <div
                      v-for="(image, index) in existingImages"
                      :key="`existing-${index}`"
                      class="relative group"
                    >
                      <img
                        :src="`/storage/${image}`"
                        :alt="`Current image ${index + 1}`"
                        class="w-full h-24 object-cover rounded-md border"
                      />
                      <button
                        type="button"
                        @click="markImageForRemoval(image)"
                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600"
                      >
                        ×
                      </button>
                      <div v-if="imageMarkedForRemoval(image)" class="absolute inset-0 bg-red-500 bg-opacity-50 flex items-center justify-center rounded-md">
                        <span class="text-white text-xs font-bold">Will be removed</span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Upload New Images -->
                <div class="space-y-4">
                  <div>
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-1">
                      Upload New Images (Maximum 10 files total)
                    </label>
                    <input
                      id="images"
                      ref="imageInput"
                      type="file"
                      multiple
                      accept="image/*"
                      @change="handleImageUpload"
                      class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                    />
                    <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF up to 2MB each</p>
                    <p v-if="errors.images" class="mt-1 text-sm text-red-600">{{ errors.images }}</p>
                  </div>

                  <!-- New Image Previews -->
                  <div v-if="imagePreviews.length > 0" class="space-y-2">
                    <h4 class="text-md font-medium text-gray-700">New Images to Upload</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                      <div
                        v-for="(preview, index) in imagePreviews"
                        :key="`new-${index}`"
                        class="relative group"
                      >
                        <img
                          :src="preview.url"
                          :alt="`New preview ${index + 1}`"
                          class="w-full h-24 object-cover rounded-md border border-green-300"
                        />
                        <button
                          type="button"
                          @click="removeNewImage(index)"
                          class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600"
                        >
                          ×
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Submit Buttons -->
              <div class="md:col-span-2 mt-8 flex justify-end space-x-4">
                <Link
                  :href="route('automobiles.show', automobile.id)"
                  class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
                >
                  Cancel
                </Link>
                <button
                  type="submit"
                  :disabled="processing"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                >
                  {{ processing ? 'Updating...' : 'Update Automobile' }}
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue'
import AppLayout from '@/Components/Layout/AppLayout.vue'
import { Inertia } from '@inertiajs/inertia'

export default {
  name: 'AutomobilesEdit',

  components: {
    AppLayout,
    Link
  },

  props: {
    automobile: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      form: {
        make: this.automobile.make || '',
        model: this.automobile.model || '',
        year: this.automobile.year || new Date().getFullYear(),
        vin: this.automobile.vin || '',
        price: this.automobile.price || '',
        mileage: this.automobile.mileage || '',
        color: this.automobile.color || '',
        body_type: this.automobile.body_type || '',
        fuel_type: this.automobile.fuel_type || '',
        transmission: this.automobile.transmission || '',
        engine_size: this.automobile.engine_size || '',
        description: this.automobile.description || '',
        status: this.automobile.status || 'available',
        images: [],
        remove_images: []
      },
      existingImages: this.automobile.images ? [...this.automobile.images] : [],
      imagePreviews: [],
      processing: false,
      errors: {}
    }
  },

  methods: {
    submit() {
      this.processing = true
      this.errors = {}

      const formData = new FormData()

      // Add form fields
      Object.keys(this.form).forEach(key => {
        if (key === 'images') return // Handle separately
        if (key === 'remove_images') {
          // Add images to be removed
          this.form.remove_images.forEach((image, index) => {
            formData.append(`remove_images[${index}]`, image)
          })
          return
        }
        if (this.form[key] !== null && this.form[key] !== '') {
          formData.append(key, this.form[key])
        }
      })

      // Add new images
      this.form.images.forEach((image, index) => {
        formData.append(`images[${index}]`, image)
      })

      // Add method override for PUT
      formData.append('_method', 'PUT')

      Inertia.post(route('automobiles.update', this.automobile.id), formData, {
        forceFormData: true,
        onSuccess: () => {
          this.processing = false
        },
        onError: (errors) => {
          this.errors = errors
          this.processing = false
        }
      })
    },

    handleImageUpload(event) {
      const files = Array.from(event.target.files)

      // Calculate total images after upload
      const totalExistingImages = this.existingImages.length - this.form.remove_images.length
      const totalNewImages = this.form.images.length

      if (files.length + totalExistingImages + totalNewImages > 10) {
        alert('Maximum 10 images allowed in total')
        this.$refs.imageInput.value = ''
        return
      }

      // Validate file types and sizes
      const validFiles = []
      const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg']

      files.forEach(file => {
        if (!validTypes.includes(file.type)) {
          alert(`${file.name} is not a valid image file`)
          return
        }

        if (file.size > 2 * 1024 * 1024) { // 2MB
          alert(`${file.name} is too large. Maximum size is 2MB`)
          return
        }

        validFiles.push(file)
      })

      // Add valid files to form and create previews
      validFiles.forEach(file => {
        this.form.images.push(file)

        const reader = new FileReader()
        reader.onload = (e) => {
          this.imagePreviews.push({
            file: file,
            url: e.target.result
          })
        }
        reader.readAsDataURL(file)
      })

      // Clear the input
      this.$refs.imageInput.value = ''
    },

    removeNewImage(index) {
      this.form.images.splice(index, 1)
      this.imagePreviews.splice(index, 1)
    },

    markImageForRemoval(image) {
      const index = this.form.remove_images.indexOf(image)
      if (index === -1) {
        this.form.remove_images.push(image)
      } else {
        this.form.remove_images.splice(index, 1)
      }
    },

    imageMarkedForRemoval(image) {
      return this.form.remove_images.includes(image)
    }
  }
}
</script>
