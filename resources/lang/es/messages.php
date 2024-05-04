<?php
return [
    "auth_signup_fail"                                  => "No ha sido posible crear su cuenta, verifique su información y vuelva a intentarlo.",
    "auth_signup_successull"                            => "Se ha enviado un correo electrónico de verificación, ingrese su correo electrónico y siga las instrucciones.",
    "auth_login_successfull"                            => "Inicio de sesión exitoso.",
    "auth_login_fail"                                   => "Datos de acceso incorrectos, verifique su información e intente nuevamente.",
    "auth_forgot_successfull_send_email"                => "Se envió un correo electrónico con un enlace de recuperación, ingrese su correo electrónico y siga las instrucciones.",
    "auth_forgot_fail_send_email"                       => "Lo sentimos, no fue posible iniciar el proceso de recuperación de acceso. Vuelva a intentarlo en un momento.",
    "auth_account_does_not_exist"                       => "Lo sentimos, no se encontró ninguna cuenta relacionada con el correo electrónico ingresado. Verifique su información e intente nuevamente.",
    "auth_account_already_existe_with_this_data"        => "Ya existe un usuario con los mismos datos.",

    "email_malformed"                                   => "Formato de correo incorrecto",
    "unable_update_data"                                => "No fue posible actualizar los datos, intente nuevamente.",
    "user_found_to_update"                              => "El usuario no existe para actualizar.",
    "email_already_in_used"                             => "El correo ya está en uso.",
    "phone_already_in_used"                             => "El teléfono ya está en uso.",
    "email_or_phone_already_used"                       => "Ya existe un usuario con el mismo correo electrónico o número de teléfono.",
    "password_wrong"                                    => "Contraseña incorrecta.",
    "without_changes"                                   => "Sin cambios.",
    "account_not_found"                                 => "No hay una cuenta con los datos ingresados, verifique su información.",
    "account_unconfirmed"                               => "Su cuenta aún no se ha verificado. Se ha enviado un enlace de verificación, ingrese su correo electrónico y siga las instrucciones.",
    "account_restricted"                                => "Lo sentimos, su cuenta ha sido restringida. Si cree que esto puede deberse a un error, comuníquese con su empresa o soporte.",
    "picture_cant_load"                                 => "No ha sido posible cargar imagen.",
    "file_cant_load"                                    => "No ha sido posible cargar archivo, intente nuevamente.",
    "save_successfull"                                  => "Datos guardados exitosamente.",
    "save_fail"                                         => "No ha sido posible guardar los datos, verifique su información y vuelva a intentarlo.",
    "file_cant_load"                                    => "No ha sido posible cargar el archivo, intente nuevamente.",

    "update_success"                                    => "Datos actualizados exitosamente.",
    "update_was_stoped"                                 => "Actualización interrumpida, algunos artículos no pertenecen a su organización. Esta actividad puede ser supervisada.",
    "update_fail"                                       => "No ha sido posible actualizar, verifique su información y vuelva a intentarlo.",

    "delete_successfull"                                => "Eliminación exitosa.",
    "delete_fail"                                       => "No ha sido posible eliminar, inténtalo de nuevo.",
    "not_found"                                         => "El elemento ya no existe o se ha eliminado anteriormente.",

    "imported_successfull"                              => "El documento se importó correctamente.",
    "imported_failed"                                   => "No se pudo procesar el documento.",
    "imported_stoped"                                   => "La actualización ha sido bloqueada. Algunos datos no son consistentes con el sistema, verifique los ID de categoría e intente nuevamente.",


    "required_more_data"                                => "Faltan datos requeridos, verifique su información e intente nuevamente.",
    "administration_start_shortly"                      => "La administración comenzará en breve.",
    "administration_start_fail"                         => "No ha sido posible iniciar administración, por favor intente nuevamente.",
    "send_successfull"                                  => "Envio exitoso.",
    "same_name_already_exist"                           => "Ya existe uno con el mismo nombre.",
    "item_not_belogn_company"                           => "El artículo no pertenece a su empresa, esta actividad puede ser supervisada.",
    "branch_open_status_changed"                        => "El estado de la sucursal cambió a",

    "opened"                                            => "Abierto.",
    "closed"                                            => "Cerrado.",
    "some_items_complements_not_belong_company"         => "Algunos de los artículos o complementos no son de su organización. Esta actividad puede ser supervisada.",
    "user_online"                                       => "En línea",
    "user_offline"                                      => "Fuera de línea",
    "user_status_change_fail"                           => "No ha sido posible actualizar el estado, inténtelo de nuevo.",
    "user_profile_not_belogn_user"                      => "El perfil no pertenece al usuario. Esta actividad puede ser supervisada.",
    "user_location_update_successfull"                  => "Ubicación actualizada correctamente.",

    "order_accept_fail"                                 => "Lo sentimos, no fue posible aceptar la orden, inténtelo de nuevo.",
    "order_accept_successfull"                          => "¡Orden tomada con exito!",
    "order_reject_successfull"                          => "Orden rechazada correctamente.",
    "order_reject_fail"                                 => "Lo sentimos, no fue posible rechazar la orden. Vuelve a intentarlo.",
    "order_does_not_exist"                              => "La orden no existe o se ha eliminado.",
    "order_status_was_changed"                          => "El estado de la orden ha cambiado.",
    "order_not_belogn_company"                          => "La orden no pertenece a su organización. Esta actividad puede ser supervisada.",
    "order_codeqr_invalid"                              => "Código de orden QR incorrecto.",
    "order_was_canceled_by_branchoffice"                => "La orden ha sido cancelado por la sucursal. Si necesita información adicional por favor contacte.",
    "order_was_cannot_canceled_is_delivered"            => "No es posible cancelar, la orden ya ha sido entregada o cancelada por el cliente o comercio.",
    "order_take_successfull"                            => "Orden # :id tomada con éxito.",
    "order_take_fail"                                   => "No ha sido posible tomar la orden, por favor intente nuevamente.",
    "order_pickup_reject_success"                       => "Entrega rechazada correctamente.",
    "order_pickup_reject_fail"                          => "No ha sido posible rechazar orden, por favor intente nuevamente.",

    "client_order_is_ready_for_pick"                    => "Su orden # :id ,está lista para recoger.",
    "client_order_is_ready_for_pick_message"            => "Tu orden está lista, puede recoger en comercio. Ingresa a la lista de órdenes para contactar o seguir la ruta hacia el comercio.",
    "client_order_was_delivered"                        => "Su orden # :id , ha sido entregada.",
    "client_order_was_delivered_message"                => "Si tiene algún problema con su orden, comuníquese con el comercio o con el servicio de asistencia.",
    "client_order_is_on_delivery"                       => "Orden # :id en turno de entrega.",
    "client_order_is_on_delivery_message"               => "Su orden # :id está en turno de entrega. Puede hacer un seguimiento desde la sección de ordenes.",
    "client_order_on_cooking"                           => "Su orden # :id está en preparación.",
    "client_order_on_cooking_message"                   => "Su pedido está en preparación. Se le notificará cuando esté listo.",

    "client_order_was_accepted"                         => "Orden # :id ha sido aceptada.",
    "client_order_was_accepted_message"                 => "Su orden ha sido aceptada. Se le notificará cuando la entrega esté lista.",
    "client_order_assigned"                             => "Orden asignada.",
    "client_order_assigned_message"                     => "Su orden # :id ha sido asignado. Se le notificará cuando esté en turno de entrega.",
    "client_order_reassigned"                           => "Orden reasignada.",
    "client_order_reassigned_message"                   => "Su orden :id ha sido reasignado a un nuevo repartidor. Se le notificará pronto cuando esté entregada.",
    "client_address_already_exist"                      => "Ya existe una dirección con el mismo nombre. Puede reemplazarla eliminándola previamenete.",
    "client_order_created"                              => "Orden registrada correctamente. Pronto recibirá notificaciones de su estado.",
    "client_order_created_fail"                         => "No se pudo registrar el orden. Verifique su información e intente nuevamente.",
    "client_order_delete"                               => "Orden #:id eliminada",
    "client_order_delete_message"                       => "La orden ha sido eliminado por el cliente.",

    "branchoffice_order_was_accepted"                   => "Orden # :id aceptada",
    "branchoffice_order_was_accepted_by_dealer"         => "La orden ha sido elegida por un repartidor. Recuerde verificar su codigo QR para confirmar entrega.",
    "branchoffice_order_was_canceled"                   => "Orden cancelada # :id",
    "branchoffice_order_was_canceled_message"           => "La orden ha sido cancelada",
    "branchoffice_order_was_canceled_by_client"         => "Orden cancelada # :id",
    "branchoffice_order_was_canceled_by_client_message" => "La orden ha sido cancelada por el cliente.",
    "branchoffice_order_was_canceled_by_dealer"         => "Orden cancelada # :id",
    "branchoffice_order_was_canceled_by_dealer_message" => "La orden ha sido cancelada por el repartidor.",
    "branchoffice_order_new"                            => "Nueva orden # :id",
    "branchoffice_order_new_message"                    => "Se ha registrado una nueva orden. Aparecerá en el módulo órdenes.",
    "branch_not_belogn_company"                         => "La sucursal no pertenece a su empresa. Esta actividad puede ser supervisada.",
    "branchoffice_restaured_success"                    => "Sucursal resraurada correctamente.",
    "branchoffice_restaured_fail"                       => "No ha sido posible restaurar sucursal. Intente nuevamente.",
    "branchoffice_not_belogn_company"                   => "La sucursal no pertenece a su empresa. Esta actividad puede ser supervisada.",
    "branchoffice_open_status_changed"                  => "El estado de la sucursal cambió a",
    "branchoffice_online"                               => "Negocios en línea.",
    "branchoffice_offline"                              => 'Negocio desconectado.',
    "branchoffice_status_change_fail"                   => "No es posible actualizar el estado. Inténtelo de nuevo.",
    "branchoffice_not_belogn_manager"                   => "La sucursal no le está asignada. Esta actividad puede ser supervisada.",

    "branchoffice_is_required"                          => "Debe registrar primero los datos de su comercio, en -Mi comercio-",

    "business_order_was_Canceled_by_client"             => "Orden # :id cancelada.",
    "business_order_was_Canceled_by_client_message"     => "La orden ha sido cancelada por el cliente.",
    "business_order_was_Canceled_by_dealer"             => "Orden # :id cancelada.",
    "business_order_was_Canceled_by_dealer_message"     => "La orden ha sido cancelada por el repartidor.",

    "manager_restaured_successfull"                     => "Gerente restaurado correctamente.",
    "manager_restaure_fail"                             => "No ha sido posible restaurar gerente. Intente nuevamente.",
    "manager_not_belogn_company"                        => "El gerente no pertenece a su empresa. Esta actividad puede ser supervisada.",
    "manager_not_found"                                 => "Gerente no encontrado. Verifique si aún existe o si  ha sido eliminado previamente.",
    "manager_disabled"                                  => "La cuenta de usuario fue inhabilitada.",

    "dealer_restaured_successfull"                      => "Repartidor restaurado correctamente.",
    "dealer_restaured_fail"                             => "No ha sido posible rasurar repartidor.",
    "dealer_not_found"                                  => "Lo sentimos, no se encontró un repartidor con el número :phone",
    "dealer_not_belogn_company"                         => "El repartidor no pertenece a su empresa. Esta actividad puede ser supervisada.",
    "dealer_assigned_success"                           => "Repartidor asignado correctamente.",
    "dealer_unavailable"                                => "Lo sentimos el repartidor no esta disponible en este momento. No es posible asignar orden.",
    "dealer_unavailables"                               => "Sin repartidores en la zona.",
    "dealer_unavailables_description"                   => "Lo sentimos de momento no hay repartidores dentro de su zona.",
    "dealer_new_order_assigned"                         => "Nueva orden asignada.",
    "dealer_new_order_assigned_message"                 => "Orden # :id asignada.",
    "dealer_order_was_removed"                          => "Orden # :id ha sido removida.",
    "dealer_order_was_removed_message"                  => "La orden ha sido removida por el comercio/sucursal.",
    "dealer_order_was_not_assigned"                     => "La orden no se le ha asignado o se ha sido reasignada a otro repartidor.",
    "dealer_order_was_assigned_directly"                => "Orden directa :id",
    "dealer_order_was_assigned_directly_message"        => "Se le ha asignado una orden de entrega de forma directa por el comercio.",
    "dealer_order_delivery_started_successfull"         => "La entrega ha sido iniciada con exito.",
    "dealer_order_delivery_started_fail"                => "No ha sido posible iniciar la entrega. Vuelve a intentarlo.",
    "dealer_order_delivery_started_already"             => "La entrega ya ha sido iniciada.",
    "dealer_order_delivery_finished"                    => "Orden # :id entregada con exito.",
    "dealer_order_delivery_finished_fail"               => "No es posible finalizar, la orden ya ha sido entregada o cancelado por el cliente o comercio.",
    "dealer_order_was_canceled"                         => "Orden # :id cancelada.",
    "dealer_order_was_canceled_message"                 => "La orden ha sido cancelada.",
    "dealer_order_was_canceled_fail"                    => "No ha sido posible cancelar la orden, inténtelo de nuevo.",
    "dealer_order_was_pickup"                           => "Orden :id recolectada.",
    "dealer_order_was_pickup_description"               => "Ahora puede iniciar la entrega cuando esté listo.",
    "dealer_order_was_canceled_pickup"                  => "Recolección orden #:id cancelada.",
    "dealer_order_was_canceled_pickup_description"      => "El repartidor ha cancelado la recolección. La orden sigue disponible para recolección.",
    "dealer_to_client_your_order_was_canceled"          => "Sú orden # :id ha sido cancelada.",
    "dealer_to_client_your_order_was_canceled_1"        => "Lo sentimos, el comerciante ha cancelado su orden porque no se encontró la dirección o es incorrecta. Si necesita ayuda, comuníquese con el cmercio.",
    "dealer_to_client_your_order_was_canceled_2"        => "Lo sentimos, el repartidor no pudo ubicarlo y decidió cancelar su orden. Si necesita ayuda, comuníquese con el comercio.",
    "dealer_to_client_your_order_was_canceled_3"        => "Lamentamos que haya tenido un problema con su orden. Si necesita ayuda, comuníquese con el comercio.",
    "dealer_to_client_your_order_was_canceled_4"        => "Lo sentimos, el distribuidor no pudo entregar su orden. Necesita ayuda para comunicarse con el minorista para obtener más información.",
    "dealer_to_client_your_order_was_canceled_default"  => "Su pedido ha sido cancelado. Si necesita ayuda, comuníquese con el comerciante para obtener más información.",
    "dealer_order_new_available"                        => "Nueva orden cercana.",
    "dealer_order_new_available_description"            => "Una nueva orden está disponible para entrega.",

    "fees_already_exist_to_state_and_city"              => "Ya existe una configuración de recompensas y tarifas para el estado y ciudad seleccionada.",
    "unable_to_send_payment"                            => "No ha sido posible enviar pago. Intente nuevamente.",
    "payment_successful"                                => "Pago exitoso.",
    "payment_nothing_to_pay"                            => "No existe balance pendiente por saldar.",

    "stripe_authentication_required"                    => "Esta transacción requiere autenticación. Inicie sesión en su cuenta de Stripe.",
    // Generic Error
    "unable_to_process_request"                         => "No es posible procesar su solicitud.",
    "successful_request"                                => "Solicitud exitosa.",
    "is_busy"                                           => "El elemento que intenta eliminar se encuentra en uso. Por favor, primero reasigne los elementos e intente nuevamente.",
    "not_belogn_company"                                => "El elemento que intente eliminar no está asociado con su organización.",
    "restore_successfull"                               => "Elemento restaurado correctamente.",
    "restore_failure"                                   => "No ha sido posible restaurar el elemento. Por favor intente nuevamente.",
    //OTP SMS
    "otp_sended"                                        => "Se ha enviado un código de verificación al número de teléfono ingresado.",
    "otp_confirmed"                                     => "Su registro ha sido verificado. Ahora puede ingresar con sus datos de cuenta, correo y password.",
    "otp_expired"                                       => "Lo sentimos, es necesario que vuelva a iniciar su proceso de registro. El tiempo de verificación ha caducado.",
    "otp_verification_fail"                             => "No ha sido posible verificar su cuenta, intente nuevamente.",
    "card_already_was_registered"                       => "El formato de pago ya ha sido registrado previamente.",
    "card_already_exist_with_another_user"              => "El formato de pago ha sido registrado por otro usuario. Si crees que es un error contacta con soporte."
];
