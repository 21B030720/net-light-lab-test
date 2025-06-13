// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: "2025-05-15",
  devtools: { enabled: true },

  // CSS
  css: ["~/assets/css/main.css"],

  // Modules
  modules: ["@nuxtjs/tailwindcss"],

  // Runtime config
  runtimeConfig: {
    public: {
      apiUrl: process.env.API_URL || "http://localhost:8000/api",
    },
  },

  // App config
  app: {
    head: {
      title: "NetLab E-Shop",
      meta: [
        { charset: "utf-8" },
        { name: "viewport", content: "width=device-width, initial-scale=1" },
        { name: "description", content: "NetLab E-Commerce Shop" },
      ],
      link: [
        { rel: "icon", type: "image/x-icon", href: "/favicon.ico" },
        {
          rel: "stylesheet",
          href: "https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap",
        },
      ],
    },
  },
});
