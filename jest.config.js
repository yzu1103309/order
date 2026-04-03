/** @type {import('jest').Config} */
const config = {
    collectCoverage: true,
    coverageThreshold: {
        global: {
            branches: 85,
            functions: 85,
            lines: 85,
            statements: 85,
        },
    },
};

module.exports = config;
