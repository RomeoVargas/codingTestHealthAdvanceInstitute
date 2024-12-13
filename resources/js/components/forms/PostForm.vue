<script>
import {useUserStore} from "@/stores/user.js";
import TagsInput from "@/components/inputs/TagsInput.vue";

export default {
    name: "PostForm",
    components: {TagsInput},
    props: {
        post: Object,
        showSubmitButton: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            formData: {
                id: null,
                title: '',
                description: '',
                tags: []
            }
        }
    },
    watch: {
        post: {
            handler(updatedPost) {
                this.formData = {
                    id: updatedPost.id || null,
                    title: updatedPost.title || '',
                    description: updatedPost.description || '',
                    tags: updatedPost.tags || []
                }
            },
            immediate: true
        }
    },
    methods: {
        submit() {
            let me = this,
                loader = me.$loading.show({
                    container: null
                })

            const userStore = useUserStore(),
                userId = userStore.loggedInUser.id,
                axiosMethod = (me.formData != null) ? 'put' : 'post',
                requestUrl =  (me.formData != null)
                    ? '/posts/update/' + userId + '/' + me.formData.id
                    : '/posts/create';

            me.formData.user_id = userId;

            axios[axiosMethod](requestUrl, me.formData).then(() => {
                loader.hide();
                me.$emit('success');
            }).catch((reason) => {
                loader.hide();
                const response = reason.response;
                alert(response.data.message);
            });
        }
    }
}
</script>

<template>
    <form @submit.prevent="submit">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input v-model="formData.title" type="text" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea v-model="formData.description" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Tags</label>
            <TagsInput v-model:tags="formData.tags" />
        </div>
        <div :class="(showSubmitButton) ? 'd-block text-end' : 'd-none'">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</template>

<style scoped>

</style>
