<?php
    namespace MyProject\Controllers;

    use MyProject\View\View;
    use MyProject\Models\Products\Product;
    use MyProject\Models\Users\User;
    use MyProject\Models\Images\Image;
    use MyProject\Services\UsersAuthService;
    use MyProject\Controllers\AbstractController;

    class MainController  extends AbstractController {

        public function main() {

            $goods = Product::findAll();
            // vardump($goods);
            // return;

            $this->view->renderHtml('main/main.php', [
                'goods' => $goods,
            ]);
        }
    }

    function vardump($var) {
        static $int=0;
        echo '<pre><b style="background: blue;padding: 1px 5px;">'.$int.'</b> ';
        var_dump($var);
        echo '</pre>';
        $int++;
    }