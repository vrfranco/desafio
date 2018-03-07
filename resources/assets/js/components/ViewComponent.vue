<template>
  <div class="visualizando">

        <div class="cabecalho">
            <div class="informacao">
                <div class="descricao">

                    <router-link to="/" class="icone voltar"></router-link>

                    <div class="endereco" v-if="item">{{item.address}}</div>

                    <div class="carregando" v-if="!item">
                        <span>Carregando</span>                    
                        <span class="icone"></span>
                    </div>

                </div>

                <div class="situacao" v-if="item">{{item.status}}</div>
            </div>
        </div>

        <div class="corpo" v-if="item">
            <div class="resultado" :class="item.status">
                <div class="recebido" v-if="item.status == 'recebido'">
                    <div class="detalhes">
                        <div class="item">Tamanho {{item.filesize | size}}</div>
                        <div class="item">Formato {{item.filetype}}</div>
                        <div class="item">Código {{item.code}}</div>
                        <div class="item">Criado {{item.created_at}}</div>
                    </div>
                    
                    <div class="conteudo" v-if="data" v-html="source"></div>
                </div>
            </div>
        </div>

  </div>
</template>

<script>

    import Prism from 'prismjs';

    import api from '../api';

    export default {

        data()
        {
            return {

                data: null,

                item: null,

                readable: [
                    'application/json',
                    'text/css',
                    'text/xml',
                    'text/html',
                    'text/json',
                    'text/plain',
                ],

                viewable: [
                    'image/jpeg',
                    'image/jpg',
                    'image/png',
                ],

            };
        },

        filters: {
            size(bytes)
            {
                var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];

                if (bytes == 0) return '0 Byte';

                var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));

                return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
            },
        },

        methods: {
            isReadable(type)
            {
                return this.readable.indexOf(type) != -1;
            },
            isViewable(type)
            {
                return this.viewable.indexOf(type) != -1;
            }
        },

        computed: {
            source()
            {                
                if( this.isReadable( this.type ) )
                {
                    const pre = document.createElement('pre');

                    const code = document.createElement('code');

                    if( this.type == 'text/html' )
                    {
                        code.classList.add('language-html');

                        console.log( this.data );

                        code.innerHTML = Prism.highlight(this.data, Prism.languages.html);

                    } else {

                        code.innerHTML = this.data;

                    }

                    pre.appendChild(code);

                    return pre.outerHTML;
                }

                if( this.isViewable( this.type ) )
                {
                    const container = document.createElement('div');

                    container.classList.add('imagem');

                    //container.style.setProperty('background-image', 'url(' + this.data + ')');

                    container.style.backgroundImage = 'url('+ this.data +')';

                    return container.outerHTML;
                }
            },
            type()
            {
                return this.item.filetype.split(';')[0];
            }
        },

        mounted()
        {
            if(this.$route.params.id)
            {
                api.url.get( this.$route.params.id ).then((data) => {

                    this.item = data;

                    if( this.item.status == 'recebido' )
                    {
                        api.url.file( data.filename ).then((file) => {
                            
                            if(this.isViewable( this.type ))
                            {
                                this.data = window.URL.createObjectURL(file);

                            } else {
                                
                                const reader = new FileReader();

                                reader.addEventListener('loadend', (e) => {
                                    this.data = e.srcElement.result;
                                });

                                reader.readAsText(file);
                            }
                        });
                    }
                });
            }
        }
        
    }

</script>
