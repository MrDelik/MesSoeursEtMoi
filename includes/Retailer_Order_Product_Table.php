<?php


class Retailer_Order_Product_Table extends WP_List_Table {
	/**
	 * Get the list of columns
	 * @return array
	 */
	public function get_columns() {
		return [
			'product' => __('Product', 'woocommerce'),
			'quantity' => __('Quantity', 'woocommerce'),
			'price' => __('Price', 'woocommerce'),
			'total' => __('Total', 'woocommerce')
		];
	}

	/**
	 * Return the list of sortable columns
	 * the second param in the value is the default sorted: true => ascending | false => descending/unordered
	 * Be aware that the array value should be the column index
	 * @return array
	 */
	public function get_sortable_columns() {
		return [
			'product' => ['product', false],
			'quantity' => ['quantity', false],
			'price' => ['price', false],
			'total' => ['total', false]
		];
	}

	/**
	 * Prepare the items to be displayed
	 * @param array $items
	 */
	public function prepare_items() {
		if( !empty($_GET['orderby']) ){
			usort($this->items, [$this, 'reorder_products']);
		}

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
	}

	public function column_default( $item, $column_name ) {
		switch( $column_name ) {
			case 'product':
			case 'quantity':
			case 'price':
			case 'total':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Reorder Products
	 * @param $proda
	 * @param $prodb
	 *
	 * @return int|lt
	 */
	public function reorder_products($proda, $prodb){
		$orderby = $_GET['orderby'];
		$order = $_GET['order'];

		switch($orderby){
			case 'product':
				$result = strcmp($proda[$orderby], $prodb[$orderby]);
				break;
			case 'quantity':
				if( (int)$proda[$orderby] > (int)$prodb[$orderby] ){
					$result = 1;
				}
				else if((int)$proda[$orderby] < (int)$prodb[$orderby]){
					$result = -1;
				}
				else{
					$result = 0;
				}
				break;
			case 'price':
				if( (float)$proda[$orderby] > (float)$prodb[$orderby] ){
					$result = 1;
				}
				else if((float)$proda[$orderby] < (float)$prodb[$orderby]){
					$result = -1;
				}
				else{
					$result = 0;
				}
				break;
			case 'total':
				if( $proda[$orderby] > $prodb[$orderby] ){
					$result = 1;
				}
				else if($proda[$orderby] < $prodb[$orderby]){
					$result = -1;
				}
				else{
					$result = 0;
				}
				break;
		}

		return $order === 'asc' ? $result : -$result;
	}

	/**
	 * Show actions button
	 * @param $item
	 *
	 * @return string
	 */
	public function column_product($item) {
		$actions = array(
			'show'      => '<a href="/wp-admin/post.php?post='.$item['id'].'&action=edit">Show</a>',
			'edit'      => '<a href="/wp-admin/post.php?post='.$item['id'].'&action=edit">Edit</a>'
		);

		return sprintf('%1$s %2$s', $item['product'], $this->row_actions($actions) );
	}

	/**
	 * Hide the table nav because not required here
	 * @param string $which
	 *
	 * @return bool|void
	 */
	public function display_tablenav( $which ) {
		return false;
	}
}