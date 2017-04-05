{
    if( $2 == "=" )
    {
        space_len = 50 - length( $1 )
        printf( "%s" , $1 )
        for( i = 0 ; i < space_len; i++ )
        {
            printf( " " )
        }
        printf( " = %s  -- %s\n" , $3 , $5 )

    }
    else
    {
        print $0
    }



}
