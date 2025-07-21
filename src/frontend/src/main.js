//import "./assets/main.css";
import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import languagePlugin from './languageImporter'

import './styles/custom.scss';

import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle';

const app = createApp(App);

app.provide('bootstrap', bootstrap);
app.use(router);
app.use(languagePlugin);

app.mount("#app");
