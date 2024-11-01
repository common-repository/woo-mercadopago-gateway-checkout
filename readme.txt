=== Checkout Gateway for Mercadopago and WooCommerce ===
Donate link: https://crplugins.com.ar
Contributors: CRPlugins
Tags: mercadopago, payments, creditcard, gateway
Requires at least: 4.8
Tested up to: 6.0
Requires PHP: 7.0
Stable tag: 1.5.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 
Plugin to connect MercadoPago's Gateway checkout with WooCommerce

== Description ==

Conectá tu tienda de WooCommerce con MercadoPago Gateway, con este modo podrás ofrecer cuotas exclusivas para cada tarjeta, incluyendo Ahora 12, Ahora 18, y cualquier otra promoción aplicable.

Atrae clientes con una tarjeta de crédito 3D en tu checkout, la cual se actualiza en tiempo real con los datos que se ingresan. Dale ese look innovador a tu sitio y aumentá las conversiones que tu tienda necesita.

Adicionalmente podrás usar el modo "Aggregator", que a diferencia del modo Gateway, te permite ofrecer las cuotas de siempre, y está disponible para todas las cuentas de MercadoPago 

== Installation ==

1. Instala el plugin a través del repositorio de plugins de WordPress
2. Activa el plugin
3. Ve a Ajustes->MercadoPago Gateway Checkout para configurar el plugin

== Frequently Asked Questions ==

= Este plugin solo se conecta con el modo Gateway? =

No, el plugin usa tanto el modo Gateway como el modo Aggregator

= Como hago para activar el modo Gateway? =

Deberás poseer tu cuenta de MercadoPago habilitada previamente para operar con modo Gateway, luego automáticamente el plugin tomará las cuotas que hayas seleccionado para cada tarjeta

= El plugin no me muestra el CFT =

Debido a inconsistencias con la API de MercadoPago, el CFT no está disponible en el 100% de los casos

= Como puedo usar el modo Aggregator en lugar del modo Gateway? =

El modo Aggregator se activa automáticamente cuando las cuotas del modo Gateway no están disponibles, no tenés que hacer nada, el plugin se maneja solo.

= Puedo usar este plugin en conjunto con otros plugins de MercadoPago? =

Si pero depende del plugin, hasta ahora se ha comprobado que hay incompatibilidad solo cuando otro plugin de MercadoPago activa el checkout customizado (es decir, muestra un formulario de pago en el checkout), con el botón de pago de MercadoPago de siempre no hay problema y pueden coexistir.

= Tengo un problema, que hago? =

Si bien el plugin es gratuito, ofrecemos soporte igualmente desde nuestro sitio https://crplugins.com.ar/

== Screenshots ==

1. Mas de 12 cuotas, incluyendo promociones bancarias
2. Muestra el CFT y el TEA de la cuota seleccionada
3. Tarjeta interactiva funcionando en tiempo real
4. Funciona con distintos emisores de tarjetas
5. Ejemplo de configuración

== Screenshots ==

1. More than 12 installments, including bank promotions
2. Shows CFT and TEA of the installment selected
3. Interactive card working in real time
4. Works with multiple cards issuers
5. Settings sample

== Changelog ==

= 1.5.4 =
Fixed bug with multiple ipn notifications
Updated compatibility with WordPress 6.0

= 1.5.3 =
Added new method for getting the order amount in the "order pay" page
Updated compatibility with WordPress 5.8

= 1.5.2 =
Hotfix for malformed js when version 1.5.1 was released

= 1.5.1 =
Hotfix for orders amount in checkout pay page

= 1.5.0 =
Customers are now able to pay for their orders in "my orders" page
Major internal dev changes

= 1.4.0 =
Fix bug where installment label was not showing in checkout
Fix empty title in the checkout
Updated WC compatibility
Updated readme

= 1.3.3 =
Fix bug where payment method was not showing in checkout
Updated WC compatibility

= 1.3.2 =
Fix bug where price was not being calculated correctly for guest customers

= 1.3.1 =
Fix bug with method id preventing the ability to enable or disable the payment method

= 1.3 =
Fix versioning and updated WordPress compatibility

= 1.2.1 =
Fix js bug, add security to ajax call

= 1.2.0 =
Fix price not calculating correctly when getting installments

= 1.1.0 =
Fix JS broken in checkout with Ajax

= 1.0 =
First release

== Upgrade Notice ==

= 1.0 =
First release
