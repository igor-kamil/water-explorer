module.exports = {
    content: ['./resources/**/*.blade.php', './resources/**/*.js', './resources/**/*.vue'],
    theme: {
        extend: {
            borderWidth: {
                1: '1px',
            },
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
