<template lang="html">
    <div class="home-container">
      <el-row class="container">
        <el-col :span="24" class="header">
                <el-col :span="10" class="logo" :class="collapsed?'logo-collapse-width':'logo-width'">{{collapsed?'':sysName}}</el-col>
                <el-col :span="4" class="userinfo">
                    <el-dropdown trigger="hover">
                        <span class="el-dropdown-link userinfo-inner">{{sysUserName}}</span>
                        <el-dropdown-menu slot="dropdown">
                            <el-dropdown-item @click.native="logoutFun">退出登录</el-dropdown-item>
                        </el-dropdown-menu>
                    </el-dropdown>
                </el-col>
        </el-col>
        <el-col :span="24" class="main">
          <aside :class="collapsed?'menu-collapsed':'menu-expanded'">
           <el-menu :default-active="$route.path" class="el-menu-vertical-demo" unique-opened router v-show="!collapsed">
             <template v-for="(item,index) in $router.options.routes" v-if="!item.hidden">
               <el-menu-item v-if="item.leaf&&item.children.length>0" :index="item.children[0].path" style="text-align: center">
                   <i :class="item.iconCls"></i>
                   {{item.children[0].name}}
               </el-menu-item>
             </template>
           </el-menu>
         </aside>
         <section class="content-container">
           <div class="grid-content bg-purple-light">
             <el-col :span="24" class="breadcrumb-container">
               <strong class="title">{{$route.name}}</strong>
               <el-breadcrumb separator="/" class="breadcrumb-inner">
                 <el-breadcrumb-item v-for="item in $route.matched" :key="item.path">{{ item.name }}</el-breadcrumb-item>
               </el-breadcrumb>
             </el-col>
             <el-col :span="24" class="content-wrapper">
               <transition name="fade" mode="out-in">
                 <router-view></router-view>
               </transition>
             </el-col>
           </div>
         </section>
       </el-col>
     </el-row>
    </div>
</template>

<script>
export default {
    data() {
        return {
            nodes:this.$router.options.routes,
            sysName: '商户管理后台',
            sysUserName: '',
            collapsed: false,
            routes:[]
        }
    },
    created: function () {
        console.log(this.$router.options.routes);
        let isLoadNodes = sessionStorage.getItem('isLoadNodes');
        if (!isLoadNodes) {
            let data = JSON.parse(window.sessionStorage.getItem('user_router'));
            this.nodes.push(...data);
            sessionStorage.setItem('isLoadNodes','true');
        }
    },
    methods: {
        //退出登录
        logoutFun: function() {
            let _this = this;
            this.$confirm('确认退出吗?', '提示', {
                //type: 'warning'
            }).then(() => {
                sessionStorage.removeItem('user');
                _this.$router.push('/login');
            }).catch(() => {

            });
        },
    },
    mounted() {
        let user = sessionStorage.getItem('user');
        if (user) {
            user = JSON.parse(user);
            this.sysUserName = user.realname || '';
        }
    }
}
</script>
<style scoped lang="scss">
.container {
    position: absolute;
    top: 0;
    bottom: 0;
    width: 100%;
    .header {
        height: 60px;
        line-height: 60px;
        background: #bbdbfb;
        color: #fff;
        .userinfo {
            text-align: right;
            padding-right: 35px;
            float: right;
            .userinfo-inner {
                cursor: pointer;
                color: #fff;
            }
        }
        .logo {
            height: 60px;
            font-size: 22px;
            padding-left: 20px;
            padding-right: 20px;
            border-color: rgba(238,241,146,0.3);
            border-right-width: 1px;
            border-right-style: solid;
        }
        .logo-width {
            width: 230px;
        }
        .logo-collapse-width {
            width: 60px;
        }
        .tools {
            padding: 0 23px;
            width: 14px;
            height: 60px;
            line-height: 60px;
            cursor: pointer;
        }
    }
    .main {
        display: flex;
        position: absolute;
        top: 60px;
        bottom: 0;
        overflow: hidden;
        aside {
            flex: 0 0 230px;
            width: 230px;
            .el-menu {
                height: 100%;
            }
            .collapsed {
                width: 60px;
                .item {
                    position: relative;
                }
                .submenu {
                    position: absolute;
                    top: 0;
                    left: 60px;
                    z-index: 99999;
                    height: auto;
                    display: none;
                }
            }
        }
        .menu-collapsed {
            flex: 0 0 60px;
            width: 60px;
        }
        .menu-expanded {
            flex: 0 0 230px;
            width: 230px;
        }
        .content-container {
            flex: 1;
            overflow-y: scroll;
            padding: 20px;
            .breadcrumb-container {
                .title {
                    width: 200px;
                    float: left;
                    color: #475669;
                }
                .breadcrumb-inner {
                    float: right;
                }
            }
            .content-wrapper {
                background-color: #fff;
                box-sizing: border-box;
            }
        }
    }
}
</style>