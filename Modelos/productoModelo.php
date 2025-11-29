<?php

	function obtenerTotalProductos($conexion) {
		$stmt = $conexion->query("SELECT COUNT(*) as total FROM Productos");
		$total = $stmt->fetch(PDO::FETCH_ASSOC);
		return $total['total'];
	}

	function obtenerProductoPorId($conexion, $idProducto) {
		$query = "
			SELECT 
				p.idProducto,
				p.nombreProducto,
				p.descripcion,
				p.precio,
				p.imagen,
				c.nombreCategoria,
				p.stock,
				ROUND(AVG(cal.calificacion), 1) AS promedio
			FROM Productos p
			LEFT JOIN Categorias c ON p.idCategoria = c.idCategoria
			LEFT JOIN Calificaciones cal ON p.idProducto = cal.idProducto
			WHERE p.idProducto = :idProducto
			GROUP BY p.idProducto
		";

		$stmt = $conexion->prepare($query);
		$stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	function obtenerProductosConCategorias($conexion) {
		$sql = "SELECT p.*, c.nombreCategoria 
				FROM Productos p 
				LEFT JOIN Categorias c ON p.idCategoria = c.idCategoria";
		$stmt = $conexion->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	function obtenerProductosConFiltro($conexion, $buscar = '', $categoria = '', $orden = '') {
		$sql = "SELECT p.*, c.nombreCategoria 
				FROM Productos p 
				JOIN Categorias c ON p.idCategoria = c.idCategoria 
				WHERE 1=1";

		$params = [];

		if (!empty($buscar)) {
			$sql .= " AND p.nombreProducto LIKE :buscar";
			$params[':buscar'] = '%' . $buscar . '%';
		}

		if (!empty($categoria) && $categoria !== 'Todas las categorías') {
			$sql .= " AND c.nombreCategoria = :categoria";
			$params[':categoria'] = $categoria;
		}

		switch ($orden) {
			case 'nombre_asc':
				$sql .= " ORDER BY p.nombreProducto ASC";
				break;
			case 'nombre_desc':
				$sql .= " ORDER BY p.nombreProducto DESC";
				break;
			case 'stock_desc':
				$sql .= " ORDER BY p.stock DESC";
				break;
			default:
				$sql .= " ORDER BY p.idProducto ASC"; // Orden por defecto
				break;
		}

		$stmt = $conexion->prepare($sql);
		$stmt->execute($params);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	function editarProducto($conexion, $datos, $imagenNueva = null) {
		$sql = "UPDATE Productos SET 
					nombreProducto = :nombre,
					descripcion = :descripcion,
					precio = :precio,
					stock = :stock,
					idCategoria = :idCategoria";

		if ($imagenNueva !== null) {
			$sql .= ", imagen = :imagen";
		}

		$sql .= " WHERE idProducto = :id";

		$stmt = $conexion->prepare($sql);

		$stmt->bindParam(':nombre', $datos['nombreProducto']);
		$stmt->bindParam(':descripcion', $datos['descripcion']);
		$stmt->bindParam(':precio', $datos['precio']);
		$stmt->bindParam(':stock', $datos['stock']);
		$stmt->bindParam(':idCategoria', $datos['categoria']);
		$stmt->bindParam(':id', $datos['idProducto']);

		if ($imagenNueva !== null) {
			$stmt->bindParam(':imagen', $imagenNueva, PDO::PARAM_LOB);
		}

		return $stmt->execute();
	}

	function agregarProducto($conexion, $datos, $imagen) {
		
		$sql = "INSERT INTO Productos (nombreProducto, descripcion, precio, stock, idCategoria, imagen)
				VALUES (:nombre, :descripcion, :precio, :stock, :idCategoria, :imagen)";
		$stmt = $conexion->prepare($sql);
		$stmt->bindParam(':nombre', $datos['nombreProducto']);
		$stmt->bindParam(':descripcion', $datos['descripcion']);
		$stmt->bindParam(':precio', $datos['precio']);
		$stmt->bindParam(':stock', $datos['stock']);
		$stmt->bindParam(':idCategoria', $datos['categoria']);
		$stmt->bindParam(':imagen', $imagen, PDO::PARAM_LOB);
		$stmt->execute();
	}	

	function obtenerProductosFiltradosPaginados($conexion, $buscar = '', $categoria = '', $limite = 8, $offset = 0) {
		$sql = "SELECT 
					p.idProducto,
					p.nombreProducto,
					p.descripcion,
					p.precio,
					p.imagen,
					c.nombreCategoria,
					ROUND(AVG(cal.calificacion), 1) AS promedio
				FROM Productos p
				LEFT JOIN Categorias c ON p.idCategoria = c.idCategoria
				LEFT JOIN Calificaciones cal ON p.idProducto = cal.idProducto
				WHERE 1=1";

		$params = [];

		if (!empty($buscar)) {
			$sql .= " AND p.nombreProducto LIKE :buscar";
			$params[':buscar'] = '%' . $buscar . '%';
		}

		if (!empty($categoria)) {
			$sql .= " AND c.nombreCategoria = :categoria";
			$params[':categoria'] = $categoria;
		}

		$sql .= " GROUP BY p.idProducto LIMIT :limite OFFSET :offset";

		$stmt = $conexion->prepare($sql);

		foreach ($params as $clave => $valor) {
			$stmt->bindValue($clave, $valor);
		}

		$stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
		$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	function contarProductosFiltrados($conexion, $buscar = '', $categoria = '') {
		$sql = "SELECT COUNT(DISTINCT p.idProducto) as total
				FROM Productos p
				LEFT JOIN Categorias c ON p.idCategoria = c.idCategoria
				WHERE 1=1";

		$params = [];

		if (!empty($buscar)) {
			$sql .= " AND p.nombreProducto LIKE :buscar";
			$params[':buscar'] = '%' . $buscar . '%';
		}

		if (!empty($categoria)) {
			$sql .= " AND c.nombreCategoria = :categoria";
			$params[':categoria'] = $categoria;
		}

		$stmt = $conexion->prepare($sql);
		$stmt->execute($params);
		return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
	}  

	function importarProductosDesdeExcel($conexion, $archivoTmp) {
		$insertados = 0;
		$errores = [];

		if (($handle = fopen($archivoTmp, "r")) !== false) {
			$fila = 0;
			while (($data = fgetcsv($handle, 1000, ",")) !== false) {
				$fila++;

				// Saltar encabezado
				if ($fila === 1) continue;

				// Asegurar que haya al menos 6 columnas
				if (count($data) < 6) continue;

				// Mapeo: A=IdProducto, B=Nombre, C=Descripcion, D=Precio, E=Stock, F=IdCategoria
				$nombre      = trim($data[1] ?? '');
				$descripcion = trim($data[2] ?? '');
				$precio      = trim($data[3] ?? '');
				$stock       = trim($data[4] ?? '');
				$idCategoria = trim($data[5] ?? '');

				// Validar datos obligatorios
				if ($nombre === '' || !is_numeric($precio) || !is_numeric($stock) || !is_numeric($idCategoria)) {
					$errores[] = "Fila $fila inválida (Nombre: '$nombre').";
					continue;
				}

				$datos = [
					'nombreProducto' => $nombre,
					'descripcion'    => $descripcion,
					'precio'         => (float)$precio,
					'stock'          => (int)$stock,
					'categoria'      => (int)$idCategoria
				];

				try {
					agregarProductos($conexion, $datos, null);
					$insertados++;
				} catch (Exception $e) {
					$errores[] = "Error en fila $fila: " . $e->getMessage();
				}
			}
			fclose($handle);
		}

		return ['insertados' => $insertados, 'errores' => $errores];
	}

	function obtenerProductosPorNombre($conexion, $nombre = '') {
		$sql = "SELECT 
					p.idProducto,
					p.nombreProducto,
					p.descripcion,
					p.precio,
					p.imagen,
					c.nombreCategoria
				FROM Productos p
				LEFT JOIN Categorias c ON p.idCategoria = c.idCategoria
				WHERE p.nombreProducto LIKE :nombre
				ORDER BY p.nombreProducto ASC";

		$stmt = $conexion->prepare($sql);
		$stmt->bindValue(':nombre', '%' . $nombre . '%');
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	function obtenerProductosConFiltroPaginados($conexion, $buscar = '', $categoria = '', $orden = '', $limite = 10, $offset = 0) {
		$sql = "SELECT p.*, c.nombreCategoria 
				FROM Productos p 
				JOIN Categorias c ON p.idCategoria = c.idCategoria 
				WHERE 1=1";

		$params = [];

		if (!empty($buscar)) {
			$sql .= " AND p.nombreProducto LIKE :buscar";
			$params[':buscar'] = '%' . $buscar . '%';
		}

		if (!empty($categoria) && $categoria !== 'Todas las categorías') {
			$sql .= " AND c.nombreCategoria = :categoria";
			$params[':categoria'] = $categoria;
		}

		switch ($orden) {
			case 'nombre_asc':
				$sql .= " ORDER BY p.nombreProducto ASC";
				break;
			case 'nombre_desc':
				$sql .= " ORDER BY p.nombreProducto DESC";
				break;
			case 'stock_desc':
				$sql .= " ORDER BY p.stock DESC";
				break;
			default:
				$sql .= " ORDER BY p.idProducto ASC";
				break;
		}

		// Agregar paginación
		$sql .= " LIMIT :limite OFFSET :offset";
		
		$stmt = $conexion->prepare($sql);
		
		// Bind de parámetros de búsqueda
		foreach ($params as $key => $value) {
			$stmt->bindValue($key, $value);
		}
		
		// Bind de parámetros de paginación
		$stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
		$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
		
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}



	function contarProductosConFiltro($conexion, $buscar = '', $categoria = '') {
		$sql = "SELECT COUNT(*) as total 
				FROM Productos p 
				JOIN Categorias c ON p.idCategoria = c.idCategoria 
				WHERE 1=1";

		$params = [];

		if (!empty($buscar)) {
			$sql .= " AND p.nombreProducto LIKE :buscar";
			$params[':buscar'] = '%' . $buscar . '%';
		}

		if (!empty($categoria) && $categoria !== 'Todas las categorías') {
			$sql .= " AND c.nombreCategoria = :categoria";
			$params[':categoria'] = $categoria;
		}

		$stmt = $conexion->prepare($sql);
		$stmt->execute($params);
		$resultado = $stmt->fetch(PDO::FETCH_ASSOC);
		return $resultado['total'];
	}

?>
