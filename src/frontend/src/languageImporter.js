import { reactive, computed } from "vue";

/**
 * Automatikusan importálja az összes nyelvi fájlt a lang/{nyelv}/ mappákból
 */
function loadLocales() {
  const locales = import.meta.glob('./lang/**/*.json', { eager: true })
  const messages = {}

  for (const path in locales) {
    // Kinyerjük a nyelv kódot a mappa nevéből (pl. ./lang/hu/menu.json -> hu)
    const matched = path.match(/\/lang\/([A-Za-z0-9-_]+)\//)
    if (matched && matched.length > 1) {
      const locale = matched[1]
      
      // Ha még nincs ez a nyelv, létrehozzuk
      if (!messages[locale]) {
        messages[locale] = {}
      }
      
      // Fájl nevét kinyerjük (pl. menu.json -> menu)
      const fileMatch = path.match(/\/([^\/]+)\.json$/)
      if (fileMatch) {
        const fileName = fileMatch[1]
        const content = locales[path].default || locales[path]
        
        // Minden fájl tartalma közvetlenül hozzáadódik a nyelv objektumhoz
        Object.assign(messages[locale], content)
      }
    }
  }

  console.log('Betöltött nyelvek:', messages)
  return messages
}

// Globális nyelvi állapot
const state = reactive({
  currentLocale: 'hu',
  fallbackLocale: 'en', // Alapértelmezett tartalék nyelv
  messages: loadLocales()
})

/**
 * Fordítás függvény fallback támogatással
 * @param {string} key - Fordítás kulcs (pl. 'home' vagy 'menu.home')
 * @param {string} fallback - Tartalék szöveg
 * @returns {string} Lefordított szöveg
 */
export function lang(key, fallback = key) {
  const keys = key.split('.')
  
  // Először az aktuális nyelven próbáljuk
  let value = getValueByKeys(state.messages[state.currentLocale], keys)
  
  // Ha nincs találat, próbáljuk a tartalék nyelven
  if (value === undefined && state.currentLocale !== state.fallbackLocale) {
    value = getValueByKeys(state.messages[state.fallbackLocale], keys)
  }
  
  return value || fallback
}

/**
 * Segédfüggvény kulcsok alapján érték kinyeréséhez
 * @param {Object} obj - Objektum
 * @param {Array} keys - Kulcsok tömbje
 * @returns {*} Megtalált érték vagy undefined
 */
function getValueByKeys(obj, keys) {
  let value = obj
  
  for (const k of keys) {
    if (value && typeof value === 'object') {
      value = value[k]
    } else {
      return undefined
    }
  }
  
  return value
}

/**
 * Nyelv váltás
 * @param {string} locale - Új nyelv kód
 */
export function setLocale(locale) {
  if (state.messages[locale]) {
    state.currentLocale = locale
    localStorage.setItem('preferred-language', locale)
    console.log('Nyelv váltás:', locale)
  } else {
    console.warn(`Nyelv nem található: ${locale}, marad: ${state.currentLocale}`)
  }
}

/**
 * Tartalék nyelv beállítása
 * @param {string} locale - Tartalék nyelv kód
 */
export function setFallbackLocale(locale) {
  if (state.messages[locale]) {
    state.fallbackLocale = locale
    console.log('Tartalék nyelv beállítva:', locale)
  } else {
    console.warn(`Tartalék nyelv nem található: ${locale}`)
  }
}

/**
 * Aktuális nyelv lekérése
 * @returns {string} Aktuális nyelv kód
 */
export function getCurrentLocale() {
  return state.currentLocale
}

/**
 * Tartalék nyelv lekérése
 * @returns {string} Tartalék nyelv kód
 */
export function getFallbackLocale() {
  return state.fallbackLocale
}

/**
 * Elérhető nyelvek listája
 * @returns {Array} Nyelv kódok tömbje
 */
export function getAvailableLocales() {
  return Object.keys(state.messages)
}

/**
 * Mentett nyelv betöltése localStorage-ből
 */
export function loadSavedLanguage() {
  const saved = localStorage.getItem('preferred-language')
  if (saved && state.messages[saved]) {
    state.currentLocale = saved
  }
}

/**
 * Vue plugin telepítő függvény
 */
export function install(app) {
  app.config.globalProperties.$lang = lang
  app.config.globalProperties.$setLocale = setLocale
  app.config.globalProperties.$setFallbackLocale = setFallbackLocale
  app.config.globalProperties.$locale = computed(() => state.currentLocale)
  
  app.provide('$lang', lang)
  app.provide('$setLocale', setLocale)
  app.provide('$setFallbackLocale', setFallbackLocale)
  app.provide('$locale', computed(() => state.currentLocale))
}

export default {
  install
}

// Inicializálás
loadSavedLanguage()