<?php ?>
<!-- <div class="contents user search">
    <article class="contain">
        <div class="contain_inner bg-white">
        <div class="search_box shop">
                    <?php // echo $this->element('search_box_shop'); ?>
        </div>
    </div>
</article>
</div> -->

<?php ?>
<!-- <div class="scrollDown more ff-en" id="scrollDown">
    <a class="scrollDown_target">scroll down<span class="scrollDown_iconBar">
            <span class="scrollDown_iconBarInner">
            </span>
        </span>
    </a>
</div> -->
<div class="contents top">
    <article class="contain">
<div id="top">
    <section>
        <div class="shoplist">
            <div class="tag">
                <h2>新着</h2>
            </div>
            <ul>
                <?php foreach ($shopDatas as $shopData): ?>
                <li><?php echo $this->element('shop_list2',['shopData'=>$shopData]); ?></li>
                <?php endforeach; ?>
                 
            </ul>
        </div>
    </section>
<!-- 
    <section>
        <div class="concept  bg-gray full-width">
            <div class="contain">
                <div >
                    <h2><span class="ff-en"></span>お店が好きな人だけの口コミ</h2>
                </div>
                     <hr />
                <div class="comment">
                    <p>お店をお気に入り登録している人の口コミを見ることができます。<br />
    好きな人だけが口コミするため、趣味が異なる人の口コミはなく、情報の信頼度が高いです。</p>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="content">
            <div class="how_to comment">
                <div class="mincho">
                    <h2>好きなお店をフォローするだけ</h2>
                </div>
                 <hr />
                <p>ファッション／インテリア／カフェなど、ジャンルを超えて好きなお店を探すことができます。<br />例えば・・<br />
「いつも買う服屋さん」が好きな人が行く「雑貨屋さん」<br />
「近所のカフェ」 が好きない人が行く「ケーキ屋 さん」<br />
「お気に入りのインテリアショップ」 が好きな人が行く「花屋さん」</p>
            </div>
        </div>
    </section> -->

</div>

</div>
</article>
</div>