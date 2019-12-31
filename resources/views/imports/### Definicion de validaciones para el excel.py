### Definicion de validaciones para el excel
### Por cada uno de los datos contenidos en el excel se tiene que verifica lo siguiente...
def excel_valido(engineapplication,aplicacion_bd,nombre_tabla,columna,registro):
    
    ### Busca si key es UNI
    if columna['key'] ==  'UNI':

        ### Buscamos si hay un registro con ese mismo dato ya agregado a la tabla
        query = text("""SELECT count(*) FROM """ + aplicacion_bd + """.""" + nombre_tabla + """ WHERE """ + columna['field'] + """ = :registro""")
        cantidad_duplicados = engineapplication.execute(query,registro = registro).scalar()

        ### Si encuentra algun valor igual retorna FALSO
        if cantidad_duplicados:

            ### Verificamos si es columna tipo fk_TB
            if 'fk_TB' in columna['field']:
                ### Se realiza proceso para dividir la cadena de la columna y obtener la tabla y la columna de FK
                columna_split =  columna['field'].split("TB")
                tabla_fk = columna_split[1].strip()
                columna_fk =  columna_split[2].split("_")[1].strip()
            else:
                columna_fk = columna['field']

            flash(u"""Existen registros duplicados en la columna: """ + columna_fk, category= "error")
                                            
            return False
    
    ### Buscamos si la columna NO permite datos nulos
    if columna['null'] == 'NO':

        ### Si el registro esta vacio entonces...
        if registro is None:

            ### Verificamos si es columna tipo fk_TB
            if 'fk_TB' in columna['field']:
                ### Se realiza proceso para dividir la cadena de la columna y obtener la tabla y la columna de FK
                columna_split =  columna['field'].split("TB")
                tabla_fk = columna_split[1].strip()
                columna_fk =  columna_split[2].split("_")[1].strip()
            else:
                columna_fk = columna['field']

            flash(u"""Existe un registro que es requerido en la columna: """ + columna_fk, category= "error")
                                                    
            return False
            
    ### Si se encuentra algun parentesis verificamos la longitud de la columna
    ### Para saber si cumple con el requerimiento
    if '(' in columna['type']:            
        length_columna = columna['type'].split("(")[1]
        length_columna =  length_columna.replace(")","")
        length_columna = int(length_columna)
        if len(str(registro)) > length_columna:

            ### Verificamos si es columna tipo fk_TB
            if 'fk_TB' in columna['field']:
                ### Se realiza proceso para dividir la cadena de la columna y obtener la tabla y la columna de FK
                columna_split =  columna['field'].split("TB")
                tabla_fk = columna_split[1].strip()
                columna_fk =  columna_split[2].split("_")[1].strip()
            else:
                columna_fk = columna['field']

            flash(u"""Existe un registro que sobrepasa la longitud de""" + str(length_columna) + """en la columna: """ + columna_fk , category= "error")
                                                
            return False

    ### Si la columna es de tipo date
    if columna['type'] == 'date':
        if not date_valido(registro):
            flash(u"""Existe un registro que no es de tipo date en la columna: """ + columna['field'], category= "error")
                                            
            return False
                         
    ### Si la columna es de tipo datetime
    if columna['type'] == 'datetime':
        if not datetime_valido(registro):
            flash(u"""Existe un registro que no es de tipo datetime en la columna: """ + columna['field'], category= "error")
                                                        
            return False
                                             
    ### Si la columna es de tipo timestamp (Solo aplica para createdDate y modifiedDate)
    if columna['type'] == 'timestamp':
        if not datetime_valido(registro):
            flash(u"""Existe un registro que no es de tipo timestamp en la columna: """ + columna['field'], category= "error")
                                                        
            return False

    ### Si la columna es de tipo Int
    if 'int' in columna['type'] and 'fk_TB' not in columna['field']:
        if not str(registro).isnumeric():
            flash(u"""Existe un registro que no es de tipo int en la columna: """ + columna['field'], category= "error")
            return False

    ### Si la columna es de tipo double
    if 'double' in columna['type']:
        if not float_valido(registro):
            flash(u"""Existe un registro que no es de tipo double en la columna: """ + columna['field'], category= "error")
            return False

    ### Si la columna es de tipo decimal
    if 'decimal' in columna['type']:
        if not float_valido(registro):
            flash(u"""Existe un registro que no es de tipo decimal en la columna: """ + columna['field'], category= "error")
            return False