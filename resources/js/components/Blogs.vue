<template>
    <div>
        <h1>Blogs SingleFileComponent UnitTested</h1>
        <ul class="pagination">
            <li class="current-page">{{ pagination.current_page }}</li>
            <li><button class="next-page" @click="getNextPage">&gt;</button></li>
        </ul>
        <ul class="blogs">
            <li class="blog" v-for="blog in blogs" :key="blog.id">
                <button class="blog-body-toggle" @click="toggle(blog)">
                    <span class="blog-title">
                        <span class="blog-selector">+</span>
                        <span class="blog-created-at">{{ blog.created_at | date_format('MM/DD/YYYY') }}</span>
                        {{ blog.title }}
                    </span>

                    <div v-show="blog.id == currentBlogId" class="blog-body">
                        <div>
                            Author: <span class="blog-author">{{ blog.author }}</span>
                        </div>
                        <div class="blog-description">{{ blog.description }}</div>
                    </div>
                </button>
            </li>
        </ul>
    </div>
</template>

<script>
    import axios from 'axios';
    import moment from 'moment';

    const API_BLOG_ENDPOINT = '/api/blogs';

    export default {
        data(){
            return {
                blogs: [],
                currentBlogId: 0,
                pagination: {}
            }
        },
        methods: {
            get(page) {

                let querystring = '';
                if (page) {
                    querystring = '?page=' + (page || 1);
                }

                let fullUrl = API_BLOG_ENDPOINT + querystring;

                return axios.get(fullUrl)
                    .then(({ data }) => {

                        this.blogs = data.data;

                        //**
                        // About this.$set() usage
                        //
                        // Using this.$set() makes nested data reactive and therefore bdd testable.
                        //
                        // ref: https://vuejs.org/v2/guide/list.html
                        //     section: "Object Change Detection Caveats"
                        //**

                        this.$set(this.pagination, 'current_page', data.current_page);

                        return this;
                    }).catch(err => {
                        console.log(`AXIOS ERROR: ${err}`);
                    });
            },
            getNextPage(){
                let page = this.pagination.current_page + 1;
                this.get(page);
            },
            toggle(blog){
                this.currentBlogId = (this.currentBlogId != blog.id) ? blog.id : 0;
            }
        },
        filters: {
            date_format(date, format){
                return moment(date).format(format || 'MM-DD-YYYY');
            }
        },
        mounted() {
            this.get();
        }
    }
</script>

<style>
    h1 {
        font-family: 'Raleway', sans-serif
    }
    .blog {
        display:block;
        min-height: 20px;
    }
    .blog-selector {
        font-size: 1.5em;
        color: #1c7430;
        font-weight:bold;
    }
    .blog-body-toggle {
        border:0;
        background-color:transparent;
        text-align:left;
    }
    .blog-title {
        font-size:1.2em;
    }
    .blog-body{
        background-color: #fcfcfc;
        padding: 5px 0 10px 20px;
    }
    .blog-description {
        margin: 5px 0;
    }
</style>