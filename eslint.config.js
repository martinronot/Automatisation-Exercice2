import js from '@eslint/js';

export default [
    js.configs.recommended,
    {
        files: ['**/*.js'],
        ignores: [
            'node_modules/**',
            'vendor/**',
            '**/*.min.js',
            'public/build/**'
        ],
        languageOptions: {
            ecmaVersion: 'latest',
            sourceType: 'module'
        },
        rules: {
            'indent': ['error', 4],
            'linebreak-style': 'off',
            'quotes': ['error', 'single'],
            'semi': ['error', 'always'],
            'no-unused-vars': 'warn',
            'no-console': 'warn'
        }
    }
];
