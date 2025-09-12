<template>
  <div class="map-container">
    <iframe
      v-if="showMap && mapUrl"
      :src="mapUrl"
      width="100%"
      height="100%"
      style="border:0;"
      allowfullscreen=""
      loading="lazy"
      referrerpolicy="no-referrer-when-downgrade"
      :title="title">
    </iframe>
    <div v-else class="map-placeholder">
      <div class="text-center text-gray-500">
        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <p class="text-sm">Interactive Map</p>
        <p class="text-xs">Location: {{ address }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MapComponent',
  props: {
    address: {
      type: String,
      required: true
    },
    title: {
      type: String,
      default: 'Location Map'
    },
    height: {
      type: String,
      default: '100%'
    },
    width: {
      type: String,
      default: '100%'
    },
    zoom: {
      type: Number,
      default: 15
    }
  },
  data() {
    return {
      showMap: true
    }
  },
  computed: {
    mapUrl() {
      // Check if we have a Google Maps API key
      const apiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY;

      if (apiKey) {
        // Use the Google Maps Embed API with the API key
        return `https://www.google.com/maps/embed/v1/place?key=${apiKey}&q=${encodeURIComponent(this.address)}&zoom=${this.zoom}`;
      } else {
        // Fallback to a static map URL
        // This is a map centered on New York as a placeholder
        return 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d193595.15830869428!2d-74.11976397304603!3d40.69766374874431!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sus!5m2!1sen!2sus';
      }
    }
  },
  mounted() {
    // Check if we have a Google Maps API key
    const apiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY;
    if (!apiKey) {
      console.warn('Google Maps API key not found. Using static map as fallback. To enable interactive maps, add VITE_GOOGLE_MAPS_API_KEY to your .env file.');
    }
  }
}
</script>

<style scoped>
.map-container {
  height: 100%;
  width: 100%;
  min-height: 200px;
}

.map-placeholder {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
  background-color: #f3f4f6;
  border-radius: 0.5rem;
  padding: 1rem;
}
</style>
