import './bootstrap';
import "tailwindcss";
import Alpine from 'alpinejs';
import Swiper from "swiper";
import $ from "jquery";
import { createApp } from "vue";
import MultiStepForm from "./components/MultiStepForm.vue";

const app = createApp({});
app.component("multi-step-form", MultiStepForm);
app.mount("#app");




window.$ = window.jQuery = $;
window.Alpine = Alpine;
const swiper = new Swiper();

Alpine.start();


