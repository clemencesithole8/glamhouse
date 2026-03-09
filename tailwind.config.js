import defaultTheme from 'tailwindcss/defaultTheme';

export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', ...defaultTheme.fontFamily.sans],
        serif: ['"Playfair Display"', ...defaultTheme.fontFamily.serif],
      },
      colors: {
        rosegold: {
          50:  '#fff7f8',
          100: '#fdecef',
          200: '#f7cfd7',
          300: '#f0b2be',
          400: '#e28a9d',
          500: '#d36a82', // primary accent
          600: '#b9536a',
          700: '#954054',
          800: '#6f2f3e',
          900: '#4a1e28',
        }
      }
    },
  },
  plugins: [],
};
