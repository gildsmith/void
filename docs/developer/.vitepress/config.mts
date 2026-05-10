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
                ],
            },
            {
                text: 'Reference',
                items: [
                    { text: 'Packages', link: '/packages' },
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
