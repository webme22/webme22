var tree_structure = {
    chart: {
        container: "#OrganiseChart6",
        levelSeparation:    80,
        siblingSeparation:  100,
        subTeeSeparation:   70,
        nodeAlign: "BOTTOM",
        scrollbar: "fancy",
        padding: 35,
        node: { HTMLclass: "evolution-tree" },
        connectors: {
            type: "curve",
            style: {
                "stroke-width": 2,
                "stroke-linecap": "round",
                "stroke": "#939393"
            }
        }
    },

    nodeStructure: {
        text: { name: "عائلة قمبر" },
        HTMLclass: "the-parent",
        children: [
            {text: { name: "أحمد(المحرق)" },image: "img/2.png",},{text: { name: "محمد(الرفاع)" },image: "img/ico.png"},
            {
                text: { name: "علي(المنامة)" },
                image: "img/ico.png",
                children: [
                    {
                    text: { name: "عبدالله" },
                    image: "img/ico.png",
                    
                    children: [
                        {
                        text: { name: "عبدالله" },
                        image: "img/ico.png",
                        children: [
                    {
                    text: { name: "عبدالله" },
                    image: "img/ico.png",
                    
                    children: [
                        {
                        text: { name: "عبدالله" },
                        image: "img/ico.png",
                        
                        },
                        {
                        text: { name: "هدى" },
                        image: "img/icon2.png"
                        },
                    
                    ]
                    },
                    {
                    text: { name: "فاطمة" },
                    image: "img/icon2.png"
                    },
                    {
                    text: { name: "الزوجة" },
                    image: "img/icon3.png",
                    link: {
                    href: "http://www.google.com",
                    target:"_blank"
                    }
                    },
                    
                ]
                        
                        },
                        {
                        text: { name: "هدى" },
                        image: "img/icon2.png"
                        },
                        
                    
                    ]
                    },
                    {
                    text: { name: "فاطمة" },
                    image: "img/icon2.png"
                    },
                    {
                    text: { name: "الزوجة" },
                    image: "img/icon3.png",
                    link: {
                    href: "http://www.google.com",
                    target:"_blank"
                    }
                    },
                    
                ]
                
            }
        ]
    }
};