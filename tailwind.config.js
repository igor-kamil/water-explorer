module.exports = {
    content: ['./resources/**/*.blade.php', './resources/**/*.js', './resources/**/*.vue'],
    theme: {
        extend: {
            borderWidth: {
                1: '1px',
            },
            screens: {
                'portrait': {'raw': '(orientation: portrait)'},
                'tall': { 'raw': '(min-height: 1500px) and (orientation: portrait)' },
            }
        },
        fontFamily: {
            sans: ['Scto Grotesk A', 'sans-serif'],
        },
    },
    corePlugins: {
        aspectRatio: false,
    },
    plugins: [require('@tailwindcss/aspect-ratio')],
}
