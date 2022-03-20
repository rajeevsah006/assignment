(function ($) {
    /**
     * Плагин для фильтрации строк таблиц путем ввода символов в поле фильтра
     *
     * @param {object} options Параметры
     *
     * @version 26.11.2018
     * @author  DimNS <atomcms@ya.ru>
     */
    $.fn.TableFilter = function (options) {
        if (typeof(options) === 'undefined') {
            options = {};
        }

        /**
         * Настройки по-умолчанию, расширяя их с помощью параметров, которые были переданы
         *
         * @type {object}
         */
        var settings = Object.assign({
            afterFilter: null
        }, options);

        // Поддерживаем цепочки вызовов
        return this.each(function () {
            $(this).keyup(function () {
                var value = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

                $($(this).attr('data-tablefilter')).find('tbody tr').removeClass("hidden").filter(function () {
                    var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                    return !~text.indexOf(value);
                }).addClass("hidden");

                if (typeof(settings.afterFilter) === 'function') {
                    settings.afterFilter();
                }
            });
        });
    };
})(jQuery);