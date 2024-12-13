<script>
import PostForm from "@/components/forms/PostForm.vue";

export default {
    name: "PostModal",
    components: {PostForm},
    props: {
        postFormData: Object,
        default() {
            return {
                id: null,
                title: '',
                description: '',
                tags: []
            }
        }
    },
    data() {
        return {
            title: 'Create New Post'
        }
    },
    watch: {
        postFormData: {
            handler(newFormData) {
                this.title = (newFormData.id != null) ? 'Edit post : ' + newFormData.title : 'Create New Post';
            },
            immediate: true
        }
    },
    methods: {
        submitForm() {
            $('#postModal form button[type=submit]').click();
        },
        processSubmittedPost(post) {
            $('#postModal').modal('hide');
            this.$emit('postSubmitted', post)
        }
    }
}
</script>

<template>
    <div class="modal fade" id="postModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">{{ title }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <PostForm :post="postFormData" @success="processSubmittedPost"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button @click="submitForm" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
