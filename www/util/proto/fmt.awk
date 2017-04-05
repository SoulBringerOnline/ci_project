{
    if( $1 == "optional" || $1 == "repeated" )
    {

        printf("    %s %s", $1 , $2)
        space_len = 20 - length( $2 )
        for( i = 0 ; i < space_len; i++ )
        {
            printf( " " )
        }
        printf( " %s " , $3)
        space_len = 30 - length( $3 )
        for( i = 0 ; i < space_len; i++ )
        {
            printf( " " )
        }
        printf( " %s %s  %s" , $4, $5, $6)

        printf("\n")
    }
    else
    {
        print $0
    }
}
