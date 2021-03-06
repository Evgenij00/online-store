<?php

    namespace MyProject\Models\Orders;

    use MyProject\Models\ActiveRecordEntity;
    use MyProject\Models\Products\Product;
    use MyProject\Models\Users\User;
    use MyProject\Models\Orders\Order;
    use MyProject\Services\Db;

    class OrderItem extends ActiveRecordEntity {

        protected $orderId;
        protected $goodsId;
        protected $goodsProperties;
        protected $count;

        protected $dproduct;

        public function getCount(): int {
            return $this->count;
        }

        public function setCount(int $count) {
            $this->count = $count;
        }

        public function getGoodsId(): int {
            return $this->goodsId;
        }

        public function getGoodsProperties(): string {
            return $this->goodsProperties;
        }

        public function setGoodsProperties(string $property) {
            $this->goodsProperties = $property;
        }

        static public function add(Product $product, Order $order): self {

            //Если такого товара с таким же свойством нет!!!
            $orderItem = new OrderItem();

            //заполняем поля товара
            $orderItem->orderId = $order->getId();
            $orderItem->goodsId = $product->getId();

            //добавляем размер
            $orderItem->goodsProperties = $_POST['product-size'];
            $orderItem->count = 1;

            //сохраняем товар в бд
            $orderItem->save();

            return $orderItem;
        }

        static public function ajax($data, User $user): ?float {
            $order = Order::findOneByColumn('user_id', $user->getId());
            // vardump($order);
            // return null;

            $orderItem = self::getById($data->id);

            //определяем метод обработки

            //если запрос на обновление размера
            if (isset($data->size)) {
                $orderItem->goodsProperties = $data->size;
                $orderItem->save();

                return null;
            //если запрос на обновление кол-ва
            } else if (isset($data->count)) {
                $orderItem->count = $data->count;
                $orderItem->save();

                $totalPrice = $order->updatePrice();
                $order->save();

                return $totalPrice;
            }

            //если запрос на удаление
            $orderItem->delete();
            $totalPrice = $order->updatePrice();
            $order->save();

            return $totalPrice;
        }

        static public function search(Product $product, Order $order) {
            $sql = "SELECT * FROM `orders_cart` WHERE order_id = :order_id AND goods_id = :goods_id AND  goods_properties = :goods_properties;";

            $db = Db::getInstace();
            $result = $db->query($sql, [
                ':order_id' => $order->getId(),
                ':goods_id' => $product->getId(),
                ':goods_properties' => $_POST['product-size']
            ], self::class);

            if ($result === []) return null;

            return $result[0];
        }

        public function getProduct(): Product {
            if ($this->dproduct === null) {
                $this->dproduct = Product::getById($this->goodsId);
            }
            return $this->dproduct;
        }

        protected static function getTableName(): string {
            return 'orders_cart';
        }

        function vardump($var) {
            static $int=0;
            echo '<pre><b style="background: blue;padding: 1px 5px;">'.$int.'</b> ';
            var_dump($var);
            echo '</pre>';
            $int++;
        }

    }