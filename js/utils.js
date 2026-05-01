/**
 * Utility functions for Alumni Tracker
 */

/**
 * Format number with thousand separators (.)
 * @param {number} num - Number to format
 * @returns {string} Formatted number
 */
const formatNumber = (num) => {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
};

/**
 * Escapes HTML characters to prevent XSS (Cross-Site Scripting)
 * @param {string} str - String to escape
 * @returns {string} Escaped string
 */
const escapeHTML = (str) => {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
};

/**
 * Shuffle array using Fisher-Yates algorithm
 * @param {array} array - Array to shuffle
 * @returns {array} Shuffled array
 */
const shuffleArray = (array) => {
    let currentIndex = array.length, randomIndex;
    while (currentIndex !== 0) {
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex--;
        [array[currentIndex], array[randomIndex]] = [array[randomIndex], array[currentIndex]];
    }
    return array;
};

/**
 * Seeded shuffle menggunakan Linear Congruential Generator (LCG).
 * Hasil shuffle bersifat deterministik: seed sama → urutan sama.
 * @param {array} array - Array yang akan di-shuffle
 * @param {number|string} seed - Angka seed (misal: timestamp login)
 * @returns {array} Array baru yang sudah di-shuffle
 */
const seededShuffle = (array, seed) => {
    let s = parseInt(seed) || 0;
    // LCG parameters (Numerical Recipes)
    const lcg = () => {
        s = (Math.imul(s, 1664525) + 1013904223) | 0;
        return (s >>> 0) / 0xFFFFFFFF;
    };
    const arr = [...array];
    for (let i = arr.length - 1; i > 0; i--) {
        const j = Math.floor(lcg() * (i + 1));
        [arr[i], arr[j]] = [arr[j], arr[i]];
    }
    return arr;
};

/**
 * Safely get value from object with multiple possible keys
 * @param {object} obj - Object to get value from
 * @param {string|array} keys - Key or array of keys to try
 * @param {*} defaultValue - Default value if not found
 * @returns {*} Value or default
 */
const safeGet = (obj, keys, defaultValue = '-') => {
    if (typeof keys === 'string') {
        return (obj[keys] && String(obj[keys]).trim() !== '') ? String(obj[keys]).trim() : defaultValue;
    }
    
    if (Array.isArray(keys)) {
        for (const key of keys) {
            if (obj[key] && String(obj[key]).trim() !== '') {
                return String(obj[key]).trim();
            }
        }
    }
    
    return defaultValue;
};

/**
 * Extract year from date string
 * @param {string} dateString - Date string
 * @returns {string} Year
 */
const extractYear = (dateString) => {
    if (!dateString) return '-';
    const parts = String(dateString).split(' ');
    return parts.pop();
};

/**
 * Format tanggal ke format Indonesia: "4 Februari 2017"
 * Mendukung: "2017-04-29", "2017-04-29 00:00:00", "29/04/2017", angka Excel serial, dll.
 * Data asli tidak diubah — hanya untuk tampilan.
 * @param {string|number} rawDate - Nilai tanggal mentah dari CSV
 * @returns {string} Tanggal terformat atau nilai asli jika tidak dikenali
 */
const formatTanggalLulus = (rawDate) => {
    if (!rawDate || rawDate === '-') return 'Tidak Publik';

    const BULAN = [
        '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    const str = String(rawDate).trim();

    // Coba parse: "2017-04-29" atau "2017-04-29 00:00:00"
    const isoMatch = str.match(/^(\d{4})-(\d{2})-(\d{2})/);
    if (isoMatch) {
        const d = parseInt(isoMatch[3], 10);
        const m = parseInt(isoMatch[2], 10);
        const y = isoMatch[1];
        if (m >= 1 && m <= 12) return `${d} ${BULAN[m]} ${y}`;
    }

    // Coba parse: "29/04/2017" atau "29-04-2017"
    const dmyMatch = str.match(/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})/);
    if (dmyMatch) {
        const d = parseInt(dmyMatch[1], 10);
        const m = parseInt(dmyMatch[2], 10);
        const y = dmyMatch[3];
        if (m >= 1 && m <= 12) return `${d} ${BULAN[m]} ${y}`;
    }

    // Coba parse angka serial Excel (hari sejak 1900-01-01)
    const serial = parseInt(str, 10);
    if (!isNaN(serial) && serial > 10000 && serial < 60000) {
        const d = new Date(Date.UTC(1899, 11, 30) + serial * 86400000);
        const day   = d.getUTCDate();
        const month = d.getUTCMonth() + 1;
        const year  = d.getUTCFullYear();
        if (month >= 1 && month <= 12) return `${day} ${BULAN[month]} ${year}`;
    }

    // Fallback: kembalikan nilai asli (sudah dalam format lain)
    return str;
};

/**
 * Check if string is a valid link
 * @param {string} value - String to check
 * @returns {boolean}
 */
const isValidLink = (value) => {
    const invalidValues = ['-', 'Tidak publik', 'Tidak dicantumkan', ''];
    return value && !invalidValues.includes(value);
};

/**
 * Debounce function
 * @param {function} func - Function to debounce
 * @param {number} delay - Delay in milliseconds
 * @returns {function} Debounced function
 */
const debounce = (func, delay) => {
    let timeoutId;
    return function (...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func(...args), delay);
    };
};
