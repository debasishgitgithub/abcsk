<main id="main">
    <section>
        <div class="container">
            <div class="row">

                <div class="col-md-9" data-aos="fade-up">
                    <div class="appendPlace">
                        <!-- <h3 class="category-title">Category: Business</h3> -->
    
                        <!-- <div class="d-md-flex post-entry-2 half">
                            <a href="single-post.html" class="me-4 thumbnail">
                                <img src="public_assets/img/post-landscape-6.jpg" alt="" class="img-fluid">
                            </a>
                            <div>
                                <div class="post-meta"><span class="date">Culture</span> <span class="mx-1">&bullet;</span> <span>Jul 5th '22</span></div>
                                <h3><a href="single-post.html">What is the son of Football Coach John Gruden, Deuce Gruden doing Now?</a></h3>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio placeat exercitationem magni voluptates dolore. Tenetur fugiat voluptates quas, nobis error deserunt aliquam temporibus sapiente, laudantium dolorum itaque libero eos deleniti?</p>
                            </div>
                        </div> -->
                    </div>

                    <div class="text-start py-4" id="pageno_append_place">
                       <!-- pageignation here  -->
                    </div>
                </div>

                <!-- side column  -->
                <div class="col-md-3">
                    <!-- ======= Sidebar ======= -->
                    <!-- <div class="aside-block"> -->
                    <!--   
                        <ul class="nav nav-pills custom-tab-nav mb-4" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-popular-tab" data-bs-toggle="pill" data-bs-target="#pills-popular" type="button" role="tab" aria-controls="pills-popular" aria-selected="true">Popular</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-trending-tab" data-bs-toggle="pill" data-bs-target="#pills-trending" type="button" role="tab" aria-controls="pills-trending" aria-selected="false">Trending</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-latest-tab" data-bs-toggle="pill" data-bs-target="#pills-latest" type="button" role="tab" aria-controls="pills-latest" aria-selected="false">Latest</button>
                            </li>
                        </ul> -->

                    <!-- <div class="tab-content" id="pills-tabContent"> -->

                    <!-- Popular -->
                    <!-- <div class="tab-pane fade show active" id="pills-popular" role="tabpanel" aria-labelledby="pills-popular-tab">
                                <div class="post-entry-1 border-bottom">
                                    <div class="post-meta"><span class="date">Sport</span> <span class="mx-1">&bullet;</span> <span>Jul 5th '22</span></div>
                                    <h2 class="mb-2"><a href="#">How to Avoid Distraction and Stay Focused During Video Calls?</a></h2>
                                    <span class="author mb-3 d-block">Jenny Wilson</span>
                                </div>
                            </div> -->
                    <!-- End Popular -->

                    <!-- Trending -->
                    <!-- <div class="tab-pane fade" id="pills-trending" role="tabpanel" aria-labelledby="pills-trending-tab">
                                <div class="post-entry-1 border-bottom">
                                    <div class="post-meta"><span class="date">Lifestyle</span> <span class="mx-1">&bullet;</span> <span>Jul 5th '22</span></div>
                                    <h2 class="mb-2"><a href="#">17 Pictures of Medium Length Hair in Layers That Will Inspire Your New Haircut</a></h2>
                                    <span class="author mb-3 d-block">Jenny Wilson</span>
                                </div>
                            </div> -->
                    <!-- End Trending -->

                    <!-- Latest -->
                    <!-- <div class="tab-pane fade" id="pills-latest" role="tabpanel" aria-labelledby="pills-latest-tab">

                                <div class="post-entry-1 border-bottom">
                                    <div class="post-meta"><span class="date">Lifestyle</span> <span class="mx-1">&bullet;</span> <span>Jul 5th '22</span></div>
                                    <h2 class="mb-2"><a href="#">17 Pictures of Medium Length Hair in Layers That Will Inspire Your New Haircut</a></h2>
                                    <span class="author mb-3 d-block">Jenny Wilson</span>
                                </div>

                            </div>  -->

                    <!-- End Latest -->
                    <!-- </div>
                    </div> -->

                    <div class="aside-block">
                        <h3 class="aside-title">Categories</h3>
                        <ul class="aside-links list-unstyled">
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Business</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Culture</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Sport</a></li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>
    </section>
</main><!-- End #main -->

<!-- End #main -->
<script>
    $(document).ready(function() {
        let contentConfig = {
            length: 3,
            currPageIndex: 0,
            get offset() {
                return this.length * this.currPageIndex
            },
            get countSeenRecords() {
                return this.offset + this.length;
            }
        }

        let pagingFormatHTML = `
                <nav aria-label="Page navigation example">
                    <ul class="pagination job-pagination mb-0 justify-content-center">
                        <li class="page-item [[IS_PREV_DISABLED]]" >
                            <button type="button" class="page-link page_btn" data-type="previous">Previous</button>
                        </li>
                        [[PAGING_NUMBER_HTML]]
                        <li class="page-item [[IS_NEXT_DISABLED]]" >
                            <button type="button" class="page-link page_btn" data-type="next">Next</button>
                        </li>
                    </ul>
                </nav>`;

        let pageignation = (recordsTotal) => {
            try {
                if (recordsTotal) {
                    var loopingTime = Math.ceil(recordsTotal / contentConfig.length);
                    var loopinPageNo = '';

                    for (let index = 0; index < loopingTime; index++) {
                        let is_active = index == contentConfig.currPageIndex ? 'active' : '';
                        loopinPageNo += `<li class="page-item ${is_active}">
                                                <button type="button" data-pageIndex="${index}" data-type="" class="page-link page_btn">${index + 1}</button>
                                            </li>`
                    }

                    var isNextDisable = recordsTotal > contentConfig.countSeenRecords ? '' : 'disabled';
                    var isPrevDisable = contentConfig.currPageIndex == 0 ? 'disabled' : '';

                    var html = pagingFormatHTML.replace(/\[\[PAGING_NUMBER_HTML\]\]/g, loopinPageNo);
                    html = html.replace(/\[\[IS_NEXT_DISABLED\]\]/g, isNextDisable);
                    html = html.replace(/\[\[IS_PREV_DISABLED\]\]/g, isPrevDisable);

                    $("body #pageno_append_place").html(html);
                } else {
                    $("body #pageno_append_place").html('');
                }
            } catch (error) {
                console.log(error);
            }
        }

        // page button click
        $('body').on('click', 'ul .page_btn', function() {
            var thisBtnType = $(this).data('type');
            if (thisBtnType == 'previous') {
                contentConfig.currPageIndex = contentConfig.currPageIndex - 1;
            } else if (thisBtnType == 'next') {
                contentConfig.currPageIndex = contentConfig.currPageIndex + 1
            } else {
                if (contentConfig.currPageIndex != parseInt($(this).data('pageindex'))) {
                    contentConfig.currPageIndex = parseInt($(this).data('pageindex'));
                } else return;
            }
            drawContent();
        });

        // set card html
        let drawContent = () => {
            $.ajax({
                url: '<?= base_url('blog/get_all') ?>',
                method: 'GET',
                data: {
                    // search_text: $("#search_text").val(),
                    limit: contentConfig.length,
                    offset: contentConfig.offset,
                },
                beforeSend: function() {
                    $(".container .appendPlace").html('');
                    $('.loading').fadeIn(200);
                },
                success: function(data) {
                    $('.loading').fadeOut(200);
                    pageignation(data.recordsTotal);
                    if (data.code == 200) {
                        let allCard = data.data.map((v, i) => {
                            
                            return v.short_content;
                        });
                        $(".container .appendPlace").append(allCard);
                        //console.log(data);
                    } else {
                        $(".container .appendPlace").html('');
                    }
                },
                error: function(error) {
                    $('.loading').fadeOut(200);
                    toastr.error('Something is wrong');
                }
            });
        }

        drawContent();
    });
</script>
<!-- ======= Footer ======= -->