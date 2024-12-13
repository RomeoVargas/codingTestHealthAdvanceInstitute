<script>
import HomeHeader from "@/components/headers/HomeHeader.vue";
import PostsTable from "@/components/tables/PostsTable.vue";
import Pagination from "@/components/paginations/Pagination.vue";
import PostModal from "@/components/modals/PostModal.vue";
import YesNoModal from "@/components/modals/YesNoModal.vue";
import {useUserStore} from "@/stores/user.js";

export default {
    name: "HomePage",
    components: {YesNoModal, PostModal, Pagination, PostsTable, HomeHeader},
    data() {
        return {
            name: 'Romz',
            searchKey: '',
            posts: {
                page: 1,
                perPage: 10,
                count: 0,
                data: []
            },
            postFormData: {
                id: null,
                title: '',
                description: '',
                tags: []
            },
            deleteModalAction: ''
        }
    },
    computed: {
        userId() {
            const userStore = useUserStore();

            return userStore.loggedInUser.id;
        }
    },
    methods: {
        setPostFormData(post) {
            this.postFormData = {
                id: post.id || null,
                title: post.title || '',
                description: post.description || '',
                tags: post.tags || []
            }
        },
        editPost(post) {
            this.setPostFormData(post);
            $('#postModal').modal('show');
        },
        confirmDeletePost(post) {
            this.setPostFormData(post);
            this.deleteModalAction = 'delete the post "' + post.title + '"';
            $('#confirmDeleteModal').modal('show');
        },
        deletePost() {
            let me = this,
                loader = me.$loading.show({
                    container: null
                });
            $('#confirmDeleteModal').modal('hide');
            axios.delete('/posts/delete/' + me.userId + '/' + me.postFormData.id).then(() => {
                loader.hide();
                me.reloadPosts();
            }).catch((reason) => {
                loader.hide();
                const response = reason.response;
                alert(response.data.message);
            });
        },
        reloadPosts(page) {
            let me = this,
                loader = me.$loading.show({
                    container: null
                });

            axios.get(
                `/posts?search=${me.searchKey}&page=${page || 1}&perPage=${me.posts.perPage}`
            ).then((response) => {
                const responseData = response.data;

                loader.hide();
                me.posts = {
                    ...me.posts,
                    page: 1,
                    count: responseData.total || 0,
                    data: responseData.records || []
                }
            }).catch((reason) => {
                loader.hide();
                const response = reason.response;
                alert(response.data.message);
            });
        }
    },
    mounted() {
        this.reloadPosts();
    }
}
</script>

<template>
    <div class="full-page py-3">
        <HomeHeader :user-name="name" pageTitle="Your Posts" />
        <div class="container">
            <div class="d-flex pt-3 pb-4">
                <h1 class="d-inline-block display-6">Your Posts</h1>
                <div class="d-inline-block ms-auto mt-auto mb-auto">
                    <button @click="setPostFormData({
                        id: null,
                        title: '',
                        description: '',
                        tags: []
                    })" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#postModal">
                        Add Post
                    </button>
                </div>
            </div>
            <div class="d-flex">
                <div class="d-inline-block mb-3">
                    <label>Search</label>
                    <input @change="reloadPosts" type="text" class="form-control" v-model="searchKey">
                </div>
            </div>
            <PostsTable @edit="editPost" @delete="confirmDeletePost" :posts="posts.data" />
            <Pagination v-if="posts.count > 0"
                @pageChange="reloadPosts"
                :current-page="posts.page"
                :total-count="posts.count"
                :num-per-page="posts.perPage"
            />
        </div>

        <PostModal @postSubmitted="reloadPosts" :post-form-data="postFormData"/>
        <YesNoModal
            id="confirmDeleteModal"
            :action="deleteModalAction"
            action-title="Confirm delete post"
            @yes="deletePost"
        />
    </div>
</template>

<style scoped>

</style>
