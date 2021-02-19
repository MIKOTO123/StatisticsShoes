import {mapMutations, mapState} from "vuex";
import * as permission_admin_category from "@common@/constant/permission_admin_category";
import {hasOneOf} from "admin@/libs/tools";


/**
 * 基础mix,用于封装一些基础的mix
 */
export default {
    data() {
        return {
            permission_admin_category: permission_admin_category,
        }
    },

    computed: {
        ...mapState({
            access: state => state.user.access,
        }),
    },

    methods: {
        ...mapMutations([
            'closeTag',
        ]),

        hasAccess(neededAccessArr) {
            if (hasOneOf(this.access, [permission_admin_category.PERMISSION_ADMIN])) {
                return true;
            } else {
                return hasOneOf(this.access, neededAccessArr)
            }
        },
        handleCloseTag() {
            this.closeTag({
                name: this.$options.name,
                query: this.$route.query,
                params: this.$route.params
            })
        },

    },
}
