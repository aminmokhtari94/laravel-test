module.exports = {
  root: true,
  env: {
    node: true,
    browser: true
  },
  extends: ['plugin:vue/essential', 'eslint:recommended', 'plugin:prettier/recommended', 'prettier'],
  plugins: ['prettier'],
  rules: {
    'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',

    // Vue
    'vue/html-quotes': ['error', 'double'],
    'vue/no-use-v-if-with-v-for': 'off'
  },
  parserOptions: {
    parser: 'babel-eslint',
    sourceType: 'module'
  }
}
