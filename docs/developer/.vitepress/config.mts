import { defineConfig } from 'vitepress'

export default defineConfig({
    title: 'Gildsmith Developer Docs',
    description: 'Developer documentation for the Gildsmith ecommerce framework.',
    lang: 'en-US',
    cleanUrls: true,
    themeConfig: {
        logo: 'https://raw.githubusercontent.com/gildsmith/art/refs/heads/master/gildsmith/icon.svg',
        nav: [
            { text: 'Guide', link: '/guide/' },
            { text: 'Packages', link: '/packages' },
        ],
        sidebar: [
            {
                text: 'Guide',
                items: [
                    { text: 'Introduction', link: '/guide/' },
                    { text: 'Getting Started', link: '/guide/getting-started' },
                    { text: 'Creating Packages', link: '/guide/creating-packages' },
                    { text: 'Resource Architecture', link: '/guide/resource-architecture' },
                    { text: 'Core Packages', link: '/guide/core-packages' },
                    { text: 'Facades and Contracts', link: '/guide/facades-and-contracts' },
                ],
            },
            {
                text: 'Packages',
                collapsed: true,
                items: [
                    { text: 'Overview', link: '/packages' },
                    {
                        text: 'Product',
                        collapsed: true,
                        items: [
                            { text: 'Overview', link: '/packages/product/' },
                            { text: 'API Reference', link: '/packages/product/api-reference' },
                        ],
                    },
                ],
            },
        ],
        socialLinks: [
            { icon: 'github', link: 'https://github.com/gildsmith/docs' },
        ],
        search: {
            provider: 'local',
        },
    },
})
