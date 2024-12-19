<a v-for="(post, index) in posts" v-bind:href="post.link" v-bind:title="post.title" <?php echo esc_attr(post_class('stm_magazine_single_list no_deco')); ?>>
    <div class="magazine-list-img" v-html="post.img"></div>
    <div class="stm-magazine-loop-data">
        <h3 class="top-content">{{post.title}}</h3>
        <div class="middle-content">
            <div v-if="post.category != ''" class="magazine-category normal-font">
                {{post.category}}
            </div>
            <div v-if="post.date != ''" class="magazine-loop-date">
                <i class="stm-icon stm-icon-ico_mag_calendar"></i>
                <div class="normal-font">{{post.date}}</div>
            </div>
            <div class="magazine-loop-reviews">
                <i class="stm-icon-ico_mag_reviews"></i>
                <div class="normal-font">{{post.comments_count}}</div>
            </div>
            <div class="magazine-loop-views">
                <i class="stm-icon-ico_mag_eye"></i>
                <div class="normal-font">{{post.post_views}}</div>
            </div>
        </div>
        <div class="bottom-content">
            <p>{{post.excerpt}}</p>
        </div>
    </div>
</a>