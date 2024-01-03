<template>
    <div v-if="fullname != ''" :style="style" class="avatar2">
        {{ initials }}
    </div>
</template>

<script>

    export default {
        name: 'CAvatar',
        props: {
            fullname: { 
                type:    String, 
                default: '' 
            },
            size:     { 
                type:    Number, 
                default: 48 
            },
            radius:   {
                type:      Number,
                default:   50,
                validator: value => value >= 0 && value <= 50
            },
            color:    { 
                type:    String, 
                default: ''
            },
            image:    { 
                type:    String, 
                default: '' 
            }
        },
        computed: {
            initials () {
                const  words = this.fullname.split(/[\s-]+/)
                return words.map(word => word.substr(0, 1)).join('').substr(0, 2).toUpperCase()
            },
            style () {
                const fontSize = this.initials.length > 2 ? this.size / 3 : this.size / 2
                return {
                    width:              this.size + 'px',
                    height:             this.size + 'px',
                    'border-radius':    this.radius + '%',
                    'font-size':        fontSize + 'px',
                    'background-color': this.color === '' ? this.toColor(this.fullname) : this.color,
                    'background-image': this.hasImage ? 'url(' + this.image + ')' : 'none'
                }
            },
            hasImage () {
                return this.image !== ''
            }
        },
        methods: {
            toColor (str) {
                let hash = 0
                if (!str) return 'black'
                
                for (const char of str.split('')) {
                    hash = (hash << (8 - hash)) + char.charCodeAt(0)
                }
                
                var colours =  ['#FF0000', '#008000' ,'#0000FF' ,'#000000' ,'#800000' ,'#808000' , '#008080' ,'#000080' ,'#FF00FF' ,'#800080' ,'#808080','#FF0000','#FA8072' ,'#52BE80','#273746','#7E5109','#3498DB'];     

                const randomMonth = colours[Math.floor(Math.random() * colours.length)];

                var Arreglo = randomMonth;

                return Arreglo;
            }
        }
    }

</script>
