/** @type {import('tailwindcss').Config} */
export default {
  content: ['./index.html', './src/**/*.{js,jsx}'],
  theme: {
    extend: {
      colors: {
        cream: '#F7F1E3',
        'cream-dim': '#EFE7D3',
        ink: '#14161A',
        'match-blue': '#16407A',
        'match-blue-dark': '#002A5B',
        'court-green': '#3C9B6B',
        'court-green-dark': '#2E7A54',
        'whistle-red': '#D9381E',
        body: '#191B1F',
        muted: '#656A63',
      },
      fontFamily: {
        display: ['"Bebas Neue"', 'sans-serif'],
        body: ['"Plus Jakarta Sans"', 'sans-serif'],
      },
      borderRadius: {
        DEFAULT: '4px',
        lg: '6px',
      },
      boxShadow: {
        tactile: '0px 4px 0px #14161A',
        'tactile-sm': '0px 3px 0px #14161A',
        'tactile-hover': '0px 6px 0px #14161A',
      },
      maxWidth: {
        content: '1280px',
      },
    },
  },
  plugins: [],
}
