<template>
    <div id="app">
        <div id="top" data-role="page scroll-action">
            <!-- PAGE -->
            <div class="pag ui-panel-wrapper">
                <TopMenu></TopMenu>
                <!-- PAGE BODY -->
                <section class="pag bdy">
                    <div class="bdy__main">
                        <router-view />
                    </div>
                    <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-1" tabindex="0" style="display: none;"></ul>
                    <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-3" tabindex="0" style="display: none;"></ul>
                </section>

                <!-- PAGE FOOTER -->
                <footer class="ftr pag">
                    <div class="disclosures">
                        Mafia track records |
                    </div>
                </footer>
            </div>
        </div>
    </div>
</template>

<script>
    import {HTTP, Routes} from '@/components/Routes/Routes'
    import TopMenu from "@/components/Main/TopMenu";
    export default {
        name: 'app',
        components: {
            TopMenu
        }
    }
</script>

<style lang="scss">
/*header*/
.ui-panel-wrapper {
	position: relative;
	min-height: inherit;
	border: 0;
	overflow-x: hidden;
	z-index: 30 !important;
}
.pag {
	background-color: #ecf0f2;
	height: 100%;
}

/*body*/
.pag.bdy {
    width: 100%;
	overflow: hidden;
	margin-top: 0;
	min-height: 768px;
    .bdy__main {
        max-width: 95%;
	    margin: 0 auto;
    }
}
.pag.bdy .bdy.content.reset {
    padding: 0;
    background-color: transparent;
    border: 0;
}
.pag.bdy .bdy.content {
    overflow: hidden;
    border: 1px solid #ddd;
    margin-bottom: 20px;
}
.__content-mount {
    background-color: white;
    border: 1px solid #ddd;
    margin-bottom: 25px;
}
.blk {
    display: block;
    overflow: hidden;
}
.__content-mount > .title {
    padding: 30px 25px 25px;
    border-bottom: 1px solid #ddd;
}
.__content-mount > h2, .__content-mount > h3, .__content-mount > h4, .__content-mount > h5 {
    margin: 20px auto 15px;
    font-weight: 500;
    letter-spacing: -.5px;
    padding-top: 20px;
}
.__content-mount > h2 {
    font-size: 2.3em;
    line-height: 1.2em;
}
.__content-mount > p, .__content-mount > h2, .__content-mount > h3, .__content-mount > h4, .__content-mount > h5 {
	padding-top: 0px;
    line-height: 1.6em;
    padding: 0 25px;
}
.__content-mount > h3 {
    font-size: 2em;
}
.__content-mount > p, .__content-mount > ol > li, .__content-mount > ul > li {
    margin-bottom: 50px;
	line-height: 1.7em;
	opacity: .6;
	font-size: 1.4em;
	padding-bottom: 10px;
}
.__content-mount > p, .__content-mount > ol, .__content-mount > ul {
	color: #555;
}
.__content-mount > p {
    font-size: 1.8em;
    font-family: Georgia,"Times New Roman",Times,serif;
}

/*footer*/
.pag.ftr {
	padding: 35px 15px;
	background-color: #374044 !important;
    .disclosures {
        font-style: italic;
        color: #fff;
        opacity: .5;
        max-width: 95%;
	    margin: 0 auto;
    }
}
.pag.bdy > div, .pag.ftr > div {
	max-width: 95%;
	margin: 0 auto;
}
.page__header-row {
    padding: 30px 25px 25px;
    background-color: #374044;
    border-bottom: 3px solid #f98c13;
    input, select {
        background-color: #2d363b;
        border: 1px solid #0094d9;
        color: #fff;
    }
    h1, h2, h3 {
        color: #bcbcbc;
    }
    h1 {
        padding: 0px 25px 0px 25px;
        line-height: 1.2em;
        font-weight: 300;
        margin-bottom: .5rem;
        font-family: inherit;
    }
    .page__header-cells {
        color: #bcbcbc;
        background-color: #2d363b;
        border: 1px solid #0094d9;
        padding-left: 0;
        padding-right: 0;
        .col {
            padding: 8px 0px 8px 0px;
            border-right: 1px solid #0094d9;
        }
        th, td {
            padding: 8px 8px 8px 8px;
            border-right: 1px solid #0094d9;
            border-bottom: 1px solid #0094d9;
            border-top: none;
            text-align: center;
        }
    }
}

/*icons*/
.icon {
    margin-left:22px;
    &:before {
        content: '';
        background-size:cover;
        position:absolute;
        width:20px;
        height:20px;
        margin-left: -24px;
        /*margin-top: 2px;*/
    }
}
.hourglass-icon-red:before {
    background: url("/static/images/hourglass-red.png") no-repeat 50% 0/95% transparent;
}
.award-icon-red:before {
    background: url("/static/images/award-red.png") no-repeat 50% 0/95% transparent;
}
.hourglass-icon:before {
    background: url("/static/images/hourglass.png") no-repeat 50% 0/95% transparent;
}
.award-icon:before {
    background: url("/static/images/award.png") no-repeat 50% 0/95% transparent;
}
.last-online-icon:before {
    background: url("/static/images/last-online.png") no-repeat 50% 0/95% transparent;
}
/*icons*/
</style>
