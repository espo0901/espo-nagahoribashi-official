<ul class="post-list clover_dec_right">
    <p class="post-news">News・お知らせ</p>
    <?php while ($query->have_posts()): $query->the_post(); ?>
        <li class="post-item">
            <div class="post-thumbnail">
                <a href="<?= get_the_permalink(); ?>"><?= get_the_post_thumbnail(); ?></a>
            </div>
            <div class="post-text">
                <p class="post-date"><?= get_the_date(); ?></p>
                <p class="post-title"><?= get_the_excerpt(); ?></p>
            </div>
        </li>
    <?php endwhile; ?>
    <div class="btn-area">
        <a href="<?= home_url('news') ?>" class="sample_btn">お知らせ一覧はこちら</a>
    </div>
    
</ul>

<style>
    .post-list{
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        margin: 50px auto;
        list-style: none;
        padding: 20px 0;
        background-color: rgba(255, 255, 255, 1);
        border: 2px solid #faa755;
        border-radius: 8px;
        z-index: 0;
    }

    .post-news{
        font-size: 2rem;
        letter-spacing: 0.2em;
        font-weight: bold;
        text-decoration: underline;
        text-decoration-color: #faa755;
        text-decoration-thickness: 3px;
        text-underline-offset: 5px;
    }

    .post-item{
        display: flex;
        padding: 5%;
        position: relative;
    }
    .post-item::after{
        position: absolute;
        content: "";
        width: 90%;
        height: 2px;
        background-color: gray;
        left: 5%;
        bottom: 0;
    }

    .post-thumbnail img{
        box-shadow: 0px 0px 19px -5px #777777;
        border-radius: 8px;
    }

    .post-text{
        padding-left: 50px;
        text-align: left;
    }

    .post-list img{
        display: block;
        margin: 0 auto;
    }

    .btn-area{
        margin: 50px 0 30px;
    }

    @media screen and (max-width: 768px){
        .post-item{
            flex-direction: column;
        }

        .post-text{
            padding-left: 0px;
            padding: 3%;
        }
    }
</style>