{
    if( $1 == "#define" && NF == 4 )
    {
        printf( "%s " , $1 )
        space_len = 40 - length( $2 )
        printf( "%s" , $2 )
        for( i = 0 ; i < space_len; i++ )
        {
            printf( " " )
        }

        printf( "%s %s\n" , $3, $4 )
    }
    else
    {
        print $0
    }



}
